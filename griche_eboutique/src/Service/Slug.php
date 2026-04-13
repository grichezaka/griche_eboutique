<?php

namespace App\Service;

use Symfony\Component\String\Slugger\AsciiSlugger;

class Slug
{
    public function slugify(string $value): string
    {
        $slugger = new AsciiSlugger();
        return strtolower($slugger->slug($value)->toString());
    }
}

