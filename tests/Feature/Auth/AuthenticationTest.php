<?php

test('login screen can be rendered', function () {
    $response = $this->get(route('mobile.login'));

    $response->assertStatus(200);
});
