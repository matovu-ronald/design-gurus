<?php

namespace App\Repositories\Eloquent;

use App\Models\Design;
use App\Repositories\Contracts\DesignInterface;

class DesignRepository extends BaseRepository implements DesignInterface
{
    public function model()
    {
        return Design::class;
    }

    public function applyTags($id, array $data)
    {
        $design = $this->find($id);
        $design->retag($data);
    }

    public function addComment($designId, array $data)
    {
        // Get the design for which we want to create a comment.
        $design = $this->find($designId);

        // Create the comment for the design
        $comment = $design->comments()->create($data);

        return $comment;
    }

    public function like($id)
    {
        // Get the design
        $design = $this->find($id);

        // Check if the design has been liked
        if ($design->isLikedByUser(auth()->id())) {
            $design->unlike();
        } else {
            $design->like();
        }

        return $design;
    }

    public function isLikedByUser($designId)
    {
        $design = $this->find($designId);

        return $design->isLikedByUser(auth()->id());
    }
}
