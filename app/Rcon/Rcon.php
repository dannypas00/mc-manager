<?php

declare(strict_types=1);

namespace App\Rcon;

/**
 * See https://developer.valvesoftware.com/wiki/Source_RCON_Protocol for
 * more information about Source RCON Packets
 *
 * @copyright 2013 Chris Churchwell
 *
 * @codeCoverageIgnore external library that didn't have a composer package
 */
class Rcon
{
    private mixed $socket;

    private $authorized;

    private $last_response;

    const PACKET_AUTHORIZE = 5;

    const PACKET_COMMAND = 6;

    const SERVERDATA_AUTH = 3;

    const SERVERDATA_AUTH_RESPONSE = 2;

    const SERVERDATA_EXECCOMMAND = 2;

    const SERVERDATA_RESPONSE_VALUE = 0;

    public function __construct(
        private readonly string $host,
        private readonly int $port,
        private readonly string $password,
        private readonly int $timeout
    ) {
    }

    public function __destruct()
    {
        $this->disconnect();
    }

    public function get_response()
    {
        return $this->last_response;
    }

    public function connect()
    {
        $this->socket = fsockopen($this->host, $this->port, $errno, $errstr, $this->timeout);

        if (!$this->socket) {
            $this->last_response = $errstr;

            return false;
        }

        //set timeout
        stream_set_timeout($this->socket, 3, 0);

        //authorize
        $auth = $this->authorize();

        if ($auth) {
            return true;
        }

        return false;
    }

    public function disconnect()
    {
        if ($this->socket) {
            fclose($this->socket);
        }
    }

    public function is_connected()
    {
        return $this->authorized;
    }

    public function send_command($command)
    {
        if (!$this->is_connected()) {
            return false;
        }

        // send command packet.
        $this->write_packet(self::PACKET_COMMAND, self::SERVERDATA_EXECCOMMAND, $command);

        // get response.
        $responsePacket = $this->read_packet();
        if (
            $responsePacket['id'] === self::PACKET_COMMAND
            && $responsePacket['type'] === self::SERVERDATA_RESPONSE_VALUE
        ) {
            $this->last_response = $responsePacket['body'];

            return $responsePacket['body'];
        }

        return false;
    }

    private function authorize()
    {
        $this->write_packet(self::PACKET_AUTHORIZE, self::SERVERDATA_AUTH, $this->password);
        $response_packet = $this->read_packet();

        if ($response_packet['type'] == self::SERVERDATA_AUTH_RESPONSE) {
            if ($response_packet['id'] == self::PACKET_AUTHORIZE) {
                $this->authorized = true;

                return true;
            }
        }

        $this->disconnect();

        return false;
    }

    /**
     * Writes a packet to the socket stream..
     */
    private function write_packet($packet_id, $packet_type, $packet_body)
    {
        /*
        Size      32-bit little-endian Signed Integer     Varies, see below.
        ID        32-bit little-endian Signed Integer    Varies, see below.
        Type      32-bit little-endian Signed Integer    Varies, see below.
        Body      Null-terminated ASCII String      Varies, see below.
        Empty String  Null-terminated ASCII String      0x00
        */

        //create packet
        $packet = pack('VV', $packet_id, $packet_type);
        $packet = $packet . $packet_body . "\x00";
        $packet = $packet . "\x00";

        // get packet size.
        $packet_size = strlen($packet);

        // attach size to packet.
        $packet = pack('V', $packet_size) . $packet;

        // write packet.
        fwrite($this->socket, $packet, strlen($packet));

    }

    private function read_packet()
    {
        //get packet size.
        $size_data = fread($this->socket, 4);
        $size_pack = unpack('V1size', $size_data);
        $size = $size_pack['size'];

        // if size is > 4096, the response will be in multiple packets.
        // this needs to be address. get more info about multi-packet responses
        // from the RCON protocol specification at
        // https://developer.valvesoftware.com/wiki/Source_RCON_Protocol
        // currently, this script does not support multi-packet responses.

        $packet_data = fread($this->socket, $size);
        $packet_pack = unpack('V1id/V1type/a*body', $packet_data);

        return $packet_pack;
    }
}
