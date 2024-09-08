<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;
    
    /**
     * test create user
     */
    public function test_create_user(): void
    {   
        
        $role = Role::create([
            'name' => 'Admin',
            'guard_name' => 'web',
        ]);

        $user = User::factory()->create([
            'name' => 'Teste User',
            'email' => 'testeuser@test.com.br',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
        ])->assignRole($role->id);
        
        $this->assertModelExists($user);
        $this->assertEquals($user->roles()->first()->id, $role->id);

    }

    /**
     * test update user
     */
    public function test_update_user(): void
    {    
        $role = Role::create([
            'name' => 'Admin',
            'guard_name' => 'web',
        ]);

        $user = User::factory()->create([
            'name' => 'Teste User',
            'email' => 'testeuser@test.com.br',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
        ])->assignRole($role->id);
        
        $this->assertModelExists($user);
        $this->assertEquals($user->roles()->first()->id, $role->id);
        
        $roleUpdate = Role::create([
            'name' => 'Membro',
            'guard_name' => 'web',
        ]);

        $user->update([
            'name' => 'Teste User Update',
            'email' => 'testeuserupdate@test.com.br',
            'password' => Hash::make('password'),
        ]);
        
        $user->removeRole($role);
        $user->assignRole($roleUpdate->id);
        $this->assertModelExists($user);
        $this->assertEquals($user->roles()->first()->id, $roleUpdate->id);
    }

    /**
     * test destroy user
     */
    public function test_destroy_user(): void
    {
        $role = Role::create([
            'name' => 'Admin',
            'guard_name' => 'web',
        ]);

        $user = User::factory()->create([
            'name' => 'Teste User',
            'email' => 'testeuser@test.com.br',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
        ])->assignRole($role->id);
        
        $this->assertModelExists($user);
        $this->assertEquals($user->roles()->first()->id, $role->id);

        $user->delete();

        $this->assertDatabaseMissing('categories', [
            'id' => $user->id,
        ]);
    }

    /** 
     * test it does not allow duplicate emails
    */
    public function test_it_does_not_allow_duplicate_emails_in_the_database()
    {
        // Cria um usuário com um e-mail específico
        $user1 = User::factory()->create([
            'email' => 'test@example.com',
        ]);

        // Espera que a tentativa de criar um segundo usuário com o mesmo e-mail lance uma exceção
        $this->expectException(QueryException::class);

        // Tenta criar outro usuário com o mesmo e-mail
        $user2 = User::factory()->create([
            'email' => 'test@example.com',
        ]);
    }
}
