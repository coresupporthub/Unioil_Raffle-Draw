<?php

namespace App\Policies;

use Spatie\Csp\Directive;
use Spatie\Csp\Policies\Basic;
use Spatie\Csp\Keyword;
class ContentSecurityPolicy extends Basic
{
    public function configure()
    {
        parent::configure();
        $this->addDirective(Directive::SCRIPT, [
                Keyword::SELF,
                'https://ajax.googleapis.com',
                'https://cdnjs.cloudflare.com',
                'https://cdn.datatables.net',
                'https://cdn.jsdelivr.net',
                'https://fonts.googleapis.com',
                'https://rsms.me'
            ])

             ->addDirective(Directive::STYLE, [
                Keyword::SELF,
                'https://cdnjs.cloudflare.com',
                'https://cdn.jsdelivr.net',
                'https://cdn.datatables.net',
                'https://rsms.me',
             ])

             ->addDirective(Directive::IMG, [
                Keyword::SELF,
                'https://cdn.jsdelivr.net',
                'data:'
             ])

             ->addDirective(Directive::FONT, [
                Keyword::SELF,
                'https://cdnjs.cloudflare.com',
                'https://rsms.me',
                'https://cdn.jsdelivr.net'
            ])
             ->addNonceForDirective(Directive::SCRIPT)
             ->addNonceForDirective(Directive::STYLE);
    }
}
