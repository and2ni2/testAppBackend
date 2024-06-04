<?php
namespace App\Services;

use App\Http\Resources\ManagerRequestsCollection;
use App\Http\Resources\ManagerRequestsResource;
use App\Models\RequestCategory;
use App\Models\RequestItem;
use App\Models\RequestMessage;
use App\Traits\ApiResponse;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Exception;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use mysql_xdevapi\Collection;

class RequestService
{
    use ApiResponse;

    protected ?\Illuminate\Contracts\Auth\Authenticatable $user;

    public function __construct() {
        $this->user = Auth::user();
    }


    /**
     * @param array $data
     * @return JsonResponse
     */
    public function create(array $data): JsonResponse
    {
        DB::beginTransaction();
        try {
            $existRequest = RequestItem::query()->where('user_id', $this->user->id)->whereDate('created_at', Carbon::today())->first();

            if ($existRequest) {
                return $this->error(['В день можно создавать только 1 заявку'], 400);
            }

            $request = RequestItem::query()->create([
                'user_id' => $this->user->id,
                'category_id' => $data['category_id'],
                'theme' => $data['theme'],
            ]);

            if (isset($data['file'])) {
                $file = $data['file'];
                $filesOriginalName = $file->getClientOriginalName();
                $extension = pathinfo($filesOriginalName, PATHINFO_EXTENSION);
                $encodedName = 'request_' . $request->id . '.' . $extension;
                $file->storeAs('public/files/user_' . $this->user->id , $encodedName);

                $path_format = 'storage/files/user_%d/%s';
                $file_path = sprintf($path_format, $this->user->id, $encodedName);
                $request->update([
                    'file_path' => $file_path
                ]);
            }

            RequestMessage::query()->create([
                'user_id' => $this->user->id,
                'request_id' => $request->id,
                'message' => $data['message'],
            ]);

            DB::commit();
            return $this->response([], ['Заявка успешно создана!']);
        } catch (Exception $exception) {
            DB::rollBack();
            return $this->error([$exception->getMessage()], 400);
        }

    }

    /**
     * @return JsonResponse
     */
    public function list():  JsonResponse
    {
        // Если авторизованный пользователь менеджер отдаем все не закрытые заявки иначе все заявки авторизованного пользователя
        if ($this->user->hasRole('manager')) {
            $requests = RequestItem::query()->whereNull('closed_at')->with(['user', 'originalMessage'])->orderBy('updated_at', 'desc')->paginate(10);

            return $this->response(ManagerRequestsResource::collection($requests)->response()->getData());
        } else {
            $requests = RequestItem::query()->where('user_id', $this->user->id)->orderBy('id', 'desc')->paginate(10);
        }

        return $this->response($requests);
    }

    /**
     * @param object $model
     * @return JsonResponse
     */
    public function show(object $model): JsonResponse
    {
        $requestItem = RequestItem::with('messages.user')->find($model->id);

        return $this->response($requestItem);
    }

    /**
     * @param object $model
     * @return JsonResponse
     */
    public function close(object $model): JsonResponse
    {
        if (!$model->closed_at) {
            RequestItem::where('id', $model->id)->update([
                'closed_at' => Carbon::now(),
            ]);

            return $this->ok();
        }

        return $this->error(['Заявка уже закрыта'], 422);
    }


    /**
     * @param array $data
     * @return JsonResponse
     */
    public function sendMessage(array $data): JsonResponse
    {
        try {
            $requestItem = RequestItem::find($data['request_id']);

            if (!$requestItem->closed_at) {
                RequestMessage::query()->create([
                    'user_id' => $this->user->id,
                    'request_id' => $data['request_id'],
                    'message' => $data['message'],
                ]);

                return $this->response([], ['Сообщение успешно добавлено']);
            }

            return $this->error(['Данная заявка уже закрыта'], 400);
        } catch (Exception $exception) {
            return $this->error([$exception->getMessage()], 400);
        }
    }

}
