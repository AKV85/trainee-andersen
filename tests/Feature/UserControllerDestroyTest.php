<?php

use App\Models\User;
use App\Notifications\AccountDeletionNotification;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Laravel\Passport\Passport;
use Tests\TestCase;


class UserControllerDestroyTest extends TestCase
{
    use DatabaseTransactions;
    protected function setUp(): void
    {
        parent::setUp();

        // Зарегистрируйте фасад Dompdf
        $this->app->bind('dompdf.wrapper', function () {
            return new PDF();
        });
    }

    public function testDestroy()
    {
        // Создаем пользователя
        $user = User::factory()->create();

        Passport::actingAs($user);

        // Выполните запрос на удаление
        $response = $this->delete(route('users.delete', $user), [], [
            'Authorization' => 'Bearer ' . $user->createToken('Test Token')->accessToken,
        ]);

//         Проверяем, что статус пользователя изменен на INACTIVE
        $this->assertEquals(User::INACTIVE, $user->fresh()->status);

        // Проверяем, что PDF файл сохранен по ожидаемому пути
        $filename = "{$user->name}_delete.pdf";
        $path = storage_path("app/pdf/{$filename}");
        $this->assertFileExists($path);

        $response->assertJson(['message' => 'Account deleted successfully']);
    }

}
