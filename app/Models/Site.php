<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class Site  extends Model
{
    protected $fillable = [
        'brand',
        'cliente_id',
        'ambiente',
        'url',
        'admin_url',
        'http_user',
        'http_psw',
        'admin_user',
        'admin_psw',

        'ssh_host',
        'ssh_user',
        'ssh_psw',
        'ssh_super_user',
        'ssh_super_psw',
        'ssh_port',
        'ssh_key_name',
        'ssh_key',
        'base_dir',

        'db_host',
        'db_name',
        'db_user',
        'db_psw',
        'db_port',
        'tunnel_ssh',
        'local_port',

        'cms',
        'cms_version',
        'php_version',
        'mysql_version',
        'redis_version',
        'composer_version',
        'elasticsearch_version',

        'sucuri',
        'varnish',
        'opcache',
        'redis',
        'cloudflare',
        'enable_ip',

        'vpn',
        'vpn_name',
        'vpn_host',
        'vpn_user',
        'vpn_psw',
        'vpn_port',

        'trello',
        'clickup',

        'cpanel_url',
        'cpanel_user',
        'cpanel_psw',

        'note',
        'attivo',
    ];
    public static function getAmbiente()
    {
        return ['Produzione', 'Staging'];
    }
    public function cmsRel()
    {
        return $this->belongsTo(Cms::class, 'cms');
    }
}
