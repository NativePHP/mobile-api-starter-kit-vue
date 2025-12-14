<?php

test('registration screen can be rendered', function () {
    $response = $this->get(route('mobile.register'));

    $response->assertStatus(200);
});
