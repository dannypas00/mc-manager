<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $user_id
 * @property string $name
 * @property bool $enabled
 * @property string|null $minecraft_host
 * @property int|null $minecraft_port
 * @property int|null $rcon_port
 * @property mixed|null $rcon_password
 * @property string|null $ftp_host
 * @property int|null $ftp_port
 * @property string|null $ftp_username
 * @property mixed|null $ftp_password
 * @property string|null $ssh_host
 * @property int|null $ssh_port
 * @property mixed|null $ssh_key
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Rcon $rcon
 * @property-read User $user
 * @method static ServerFactory factory($count = null, $state = [])
 * @method static Builder<static>|Server newModelQuery()
 * @method static Builder<static>|Server newQuery()
 * @method static Builder<static>|Server query()
 * @method static Builder<static>|Server whereCreatedAt($value)
 * @method static Builder<static>|Server whereEnabled($value)
 * @method static Builder<static>|Server whereFtpHost($value)
 * @method static Builder<static>|Server whereFtpPassword($value)
 * @method static Builder<static>|Server whereFtpPort($value)
 * @method static Builder<static>|Server whereFtpUsername($value)
 * @method static Builder<static>|Server whereId($value)
 * @method static Builder<static>|Server whereMinecraftHost($value)
 * @method static Builder<static>|Server whereMinecraftPort($value)
 * @method static Builder<static>|Server whereName($value)
 * @method static Builder<static>|Server whereRconPassword($value)
 * @method static Builder<static>|Server whereRconPort($value)
 * @method static Builder<static>|Server whereSshHost($value)
 * @method static Builder<static>|Server whereSshKey($value)
 * @method static Builder<static>|Server whereSshPort($value)
 * @method static Builder<static>|Server whereUpdatedAt($value)
 * @method static Builder<static>|Server whereUserId($value)
 * @property-read mixed $filesystem
 * @mixin Eloquent
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperServer {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $two_factor_secret
 * @property string|null $two_factor_recovery_codes
 * @property string|null $two_factor_confirmed_at
 * @property string|null $remember_token
 * @property int|null $current_team_id
 * @property string|null $profile_photo_path
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read DatabaseNotificationCollection<int, DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read string $profile_photo_url
 * @property-read Collection<int, Server> $servers
 * @property-read int|null $servers_count
 * @property-read Collection<int, PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @method static UserFactory factory($count = null, $state = [])
 * @method static Builder<static>|User newModelQuery()
 * @method static Builder<static>|User newQuery()
 * @method static Builder<static>|User query()
 * @method static Builder<static>|User whereCreatedAt($value)
 * @method static Builder<static>|User whereCurrentTeamId($value)
 * @method static Builder<static>|User whereEmail($value)
 * @method static Builder<static>|User whereEmailVerifiedAt($value)
 * @method static Builder<static>|User whereId($value)
 * @method static Builder<static>|User whereName($value)
 * @method static Builder<static>|User wherePassword($value)
 * @method static Builder<static>|User whereProfilePhotoPath($value)
 * @method static Builder<static>|User whereRememberToken($value)
 * @method static Builder<static>|User whereTwoFactorConfirmedAt($value)
 * @method static Builder<static>|User whereTwoFactorRecoveryCodes($value)
 * @method static Builder<static>|User whereTwoFactorSecret($value)
 * @method static Builder<static>|User whereUpdatedAt($value)
 * @mixin Eloquent
 * @noinspection PhpFullyQualifiedNameUsageInspection
 * @noinspection PhpUnnecessaryFullyQualifiedNameInspection
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperUser {}
}

