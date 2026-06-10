<?php

namespace App\Services;

use App\Models\WaTemplate;

class WaTemplateService
{
    /**
     * Render template WA berdasarkan kode.
     */
    public static function render(string $kode, array $data = []): ?string
    {
        $template = WaTemplate::where('kode', $kode)
            ->where('aktif', 1)
            ->first();

        if (! $template) {
            return null;
        }

        $replace = [];

        foreach ($data as $key => $value) {
            $replace['{'.$key.'}'] = $value;
        }

        return strtr($template->isi, $replace);
    }
}
