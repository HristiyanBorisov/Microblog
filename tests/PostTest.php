<?php

use App\Database;
use App\Models\Post;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Faker\Generator as Generator;

class PostTest extends TestCase
{

    private $pdo;

    private Post $post;

    private UuidInterface $postId;

    private Generator $faker;

    public function setUp(): void
    {
        $this->pdo = Database::connect($_ENV['DB_NAME_TEST']);

        $this->pdo->exec('DELETE FROM posts');

        $this->post = new Post($this->pdo);

        $this->createPost();
    }

    public function test_list_post_success(): void
    {
        $this->createPost();

        $result = $this->post->find($this->postId);

        $this->assertNotEmpty($result);
    }

    public function test_list_post_not_exists_fail(): void
    {
        $this->createPost();

        $result = $this->post->find(Uuid::uuid4());

        $this->assertNull($result);
    }

    public function test_create_post_success(): void
    {
        $this->faker = \Faker\Factory::create();

        $result = $this->post->create(
            [
                'title' => $this->faker->text(15),
                'content' => $this->faker->text(),
            ]
        );

        $this->assertTrue($result);
    }

    public function test_update_post_success(): void
    {
        $this->createPost();

        $fake = \Faker\Factory::create();

        $result = $this->post->update(
            $this->postId,
            [
                'title' => $fake->text(15),
                'content' => $fake->text(),
            ]
        );

        $this->assertTrue($result);
    }

    public function test_delete_post_success(): void
    {
        $this->createPost();
        $result = $this->post->delete($this->postId);
        $this->assertTrue($result);
    }

    private function createPost(): void
    {
        $this->faker = \Faker\Factory::create();

        $id = Uuid::uuid4();

        $stmt = $this->pdo->prepare("
            INSERT INTO posts (id, title, content)
            VALUES (:id, :title, :content)
        ");

        $stmt->execute([
            ':id'      => $id->toString(),
            ':title'   => $this->faker->text(15),
            ':content' => $this->faker->text(),
        ]);

        $this->postId = $id;
    }
}
