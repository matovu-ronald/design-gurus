<?php

namespace App\Repositories\Contracts;

interface DesignInterface
{
    public function applyTags($id, array $data);
    public function addComment($designId, array $data);
}
