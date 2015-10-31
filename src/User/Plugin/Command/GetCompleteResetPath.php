<?php namespace Anomaly\UsersModule\User\Plugin\Command;

use Anomaly\SettingsModule\Setting\Contract\SettingRepositoryInterface;
use Anomaly\UsersModule\User\Contract\UserInterface;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Encryption\Encrypter;

/**
 * Class GetCompleteResetPath
 *
 * @link          http://anomaly.is/streams-platform
 * @author        AnomalyLabs, Inc. <hello@anomaly.is>
 * @author        Ryan Thompson <ryan@anomaly.is>
 * @package       Anomaly\UsersModule\User\Plugin\Command
 */
class GetCompleteResetPath implements SelfHandling
{

    /**
     * The user instance.
     *
     * @var UserInterface
     */
    protected $user;

    /**
     * Create a new GetCompleteResetPath instance.
     *
     * @param UserInterface $user
     */
    public function __construct(UserInterface $user)
    {
        $this->user = $user;
    }

    /**
     * Handle the command.
     *
     * @param SettingRepositoryInterface $settings
     * @return string|null
     */
    public function handle(SettingRepositoryInterface $settings, Encrypter $encrypter)
    {
        $email = $encrypter->encrypt($this->user->getEmail());
        $code  = $encrypter->encrypt($this->user->getResetCode());

        $query = "?email={$email}&code={$code}";

        return $settings->value('anomaly.module.users::complete_reset_path', 'reset/complete') . $query;
    }
}
