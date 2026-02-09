<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('guest is redirected to login from admin dashboard', function () {
    $response = $this->get(route('admin.dashboard'));
    $response->assertRedirect(route('login'));
});

test('student cannot access admin dashboard', function () {
    $student = User::factory()->create(['role' => 0]);
    
    $response = $this->actingAs($student)->get(route('admin.dashboard'));
    
    $response->assertRedirect('/');
    $response->assertSessionHas('error', 'You do not have Admin access.');
});

test('admin can access admin dashboard', function () {
    $admin = User::factory()->create(['role' => 1]);
    
    $response = $this->actingAs($admin)->get(route('admin.dashboard'));
    
    $response->assertStatus(200);
});

test('guest is redirected to login from student exam list', function () {
    $response = $this->get(route('student.exams'));
    $response->assertRedirect(route('login'));
});

test('admin cannot access student exam list', function () {
    $admin = User::factory()->create(['role' => 1]);
    
    $response = $this->actingAs($admin)->get(route('student.exams'));
    
    $response->assertRedirect(route('admin.dashboard'));
    $response->assertSessionHas('error', 'Admins cannot take exams.');
});

test('student can access student exam list', function () {
    $student = User::factory()->create(['role' => 0]);
    
    $response = $this->actingAs($student)->get(route('student.exams'));
    
    $response->assertStatus(200);
});
