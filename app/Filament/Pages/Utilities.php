<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class Utilities extends Page
{
    protected static string $resource = UtilityResource::class;
    protected static bool $shouldRegisterNavigation = false;
    public ?string $countString = null;
    protected static string $view = 'filament.pages.utilities';
    public ?string $phpFrom = 'php@8.1';
    public ?string $phpTo = 'php@8.3';
    public ?int $passwordSpecial = 0;
    public ?string $htUser = null;
    public ?string $htPassword = null;
    public ?string $htResult = null;
    public ?int $passwordLength = null;
    public ?string $convertPassword = null;
    public ?string $convertType = 'bcrypt';
    public ?string $cmsType = 'm2';
    public ?string $cmsUser = null;
    public ?string $cmsName = null;
    public ?string $cmsLastname = null;
    public ?string $cmsEmail = null;
    public ?string $cmsPassword = null;
    public ?string $magentoDir = null;
    public ?string $magentoBaseUrl = null;

    public ?string $magentoDbHost = 'localhost';
    public ?string $magentoDbName = null;
    public ?string $magentoDbUser = 'root';
    public ?string $magentoDbPassword = null;

    public ?string $magentoAdminFirstname = null;
    public ?string $magentoAdminLastname = null;
    public ?string $magentoAdminEmail = null;
    public ?string $magentoAdminUser = null;
    public ?string $magentoAdminPassword = null;

    public ?string $magentoLanguage = 'en_US';
    public ?string $magentoCurrency = 'EUR';
    public ?string $magentoTimezone = 'Europe/Rome';
    public static function canAccess(): bool
    {
        return auth()->user()->hasAnyRole(['admin', 'threecommerce']);
    }

    public function generateCmsUserCommand()
    {
        if (!$this->cmsUser || !$this->cmsPassword)
            return;

        switch ($this->cmsType) {

            case 'm2':

                $command = "php bin/magento admin:user:create " .
                    "--admin-user={$this->cmsUser} " .
                    "--admin-password='{$this->cmsPassword}' " .
                    "--admin-email={$this->cmsEmail} " .
                    "--admin-firstname='{$this->cmsName}' " .
                    "--admin-lastname='{$this->cmsLastname}'";

                break;

            case 'm1':

                $hash = md5($this->cmsPassword);

                $command = "INSERT INTO admin_user (firstname, lastname, email, username, password, created, lognum, reload_acl_flag, is_active, extra) VALUES " .
                    "('{$this->cmsName}', '{$this->cmsLastname}', '{$this->cmsEmail}', '{$this->cmsUser}', '{$hash}', NOW(), 0, 0, 1, '');";

                break;

            case 'wp':

                $command = "wp user create {$this->cmsUser} {$this->cmsEmail} " .
                    "--user_pass='{$this->cmsPassword}' " .
                    "--role=administrator " .
                    "--first_name='{$this->cmsName}' " .
                    "--last_name='{$this->cmsLastname}'";

                break;

            default:
                return;
        }

        $this->htResult = $command;
        $this->dispatch('open-modal', id: 'result-modal');
    }

    public function countChars()
    {
        $count = strlen($this->countString ?? '');
        $this->htResult = "Numero caratteri: {$count}";
        $this->dispatch('open-modal', id: 'result-modal');
    }

    public function convertPasswordHash()
    {
        if (!$this->convertPassword)
            return;
        $password = $this->convertPassword;
        switch ($this->convertType) {
            case 'md5':
                $hash = md5($password);
                break;
            case 'bcrypt':
                $hash = password_hash($password, PASSWORD_BCRYPT);
                break;
            default:
                $hash = md5($password);
                break;
        }
        $this->htResult = $hash;
        $this->dispatch('open-modal', id: 'result-modal');
    }

    public function generatePassword()
    {
        $length = $this->passwordLength ?? 12;
        $special = $this->passwordSpecial ?? 0;

        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';

        if ($special)
            $chars .= '!@#$%^&*()_+-=';

        $password = '';

        for ($i = 0; $i < $length; $i++)
            $password .= $chars[random_int(0, strlen($chars) - 1)];
        $this->htResult = "{$password}";
        $this->dispatch('open-modal', id: 'result-modal');
    }

    public function generateHtpasswd()
    {
        if (!$this->htUser || !$this->htPassword)
            return;
        $hash = password_hash($this->htPassword, PASSWORD_BCRYPT);
        $this->htResult = "{$this->htUser}:{$hash}";
        $this->dispatch('open-modal', id: 'result-modal');
    }

    public function switchPhp()
    {
        if (!$this->phpFrom || !$this->phpTo)
            return;

        $command = "sudo brew services stop {$this->phpFrom} && " .
            "sudo brew services start {$this->phpTo} && " .
            "brew unlink {$this->phpFrom} && " .
            "brew link {$this->phpTo} && " .
            "valet use {$this->phpTo} --force";
        $this->htResult = "{$command}";
        $this->dispatch('open-modal', id: 'result-modal');
    }
    public function generateMagentoInstall()
    {
        if (
            !$this->magentoDir ||
            !$this->magentoBaseUrl ||
            !$this->magentoDbName ||
            !$this->magentoDbPassword ||
            !$this->magentoAdminUser ||
            !$this->magentoAdminPassword
        ) return;

        $composer = "composer create-project --repository-url=https://repo.magento.com/ magento/project-community-edition {$this->magentoDir}";

        $install = "cd {$this->magentoDir} && php bin/magento setup:install " .
            "--base-url={$this->magentoBaseUrl} " .
            "--db-host={$this->magentoDbHost} " .
            "--db-name={$this->magentoDbName} " .
            "--db-user={$this->magentoDbUser} " .
            "--db-password='{$this->magentoDbPassword}' " .
            "--admin-firstname='{$this->magentoAdminFirstname}' " .
            "--admin-lastname='{$this->magentoAdminLastname}' " .
            "--admin-email={$this->magentoAdminEmail} " .
            "--admin-user={$this->magentoAdminUser} " .
            "--admin-password='{$this->magentoAdminPassword}' " .
            "--language={$this->magentoLanguage} " .
            "--currency={$this->magentoCurrency} " .
            "--timezone={$this->magentoTimezone} " .
            "--use-rewrites=1";

        $this->htResult = $composer . "\n\n" . $install;

        $this->dispatch('open-modal', id: 'result-modal');
    }
}
