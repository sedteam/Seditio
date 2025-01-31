<?php

/**
 * Send email class using SMTP Authentication
 * @class Email
 */

class Email
{
    const CRLF = "\r\n";
    const TLS = 'tcp';
    const SSL = 'ssl';
    const OK = 250;

    protected $server;
    protected $port;
    protected $localhost;
    protected $socket;
    protected $charset;
    protected $username;
    protected $password;
    protected $connect_timeout;
    protected $response_timeout;
    protected $headers = [];
    protected $from = [];
    protected $to = [];
    protected $cc = [];
    protected $reply_to = [];
    protected $bcc = [];
    protected $subject;
    protected $message_text;
    protected $message_html;
    protected $attachments = [];
    protected $log = [];
    protected $tls = false;
    protected $protocol;
    protected $boundary;
    protected $logFile = 'email.log';

    /**
     * Email constructor.
     * @param string $server SMTP server address
     * @param int $port SMTP server port
     * @param int $connection_timeout Connection timeout in seconds
     * @param int $response_timeout Response timeout in seconds
     * @param string $protocol Connection protocol (TLS, SSL, or empty for plain TCP)
     */
    public function __construct($server, $port = 25, $connection_timeout = 30, $response_timeout = 8, $protocol = '')
    {
        $this->server = $server;
        $this->port = $port;
        $this->localhost = $server;
        $this->connect_timeout = $connection_timeout;
        $this->response_timeout = $response_timeout;
        $this->protocol = $protocol;
        $this->charset = 'utf-8';
        $this->boundary = md5(uniqid(time()));
        $this->setProtocol($protocol);

        $this->headers['MIME-Version'] = '1.0';
        $this->headers['X-Mailer'] = 'PHP/' . phpversion();
    }

    /**
     * Set the log file path
     * @param string $path Path to the log file
     */
    public function setLogFile($path)
    {
        $this->logFile = $path;
    }

    /**
     * Add a recipient to the email
     * @param string $address Email address
     * @param string $name Recipient name
     */
    public function addTo($address, $name = '')
    {
        $this->to[] = [$address, $name];
    }

    /**
     * Add a CC recipient to the email
     * @param string $address Email address
     * @param string $name Recipient name
     */
    public function addCc($address, $name = '')
    {
        $this->cc[] = [$address, $name];
    }

    /**
     * Add a BCC recipient to the email
     * @param string $address Email address
     * @param string $name Recipient name
     */
    public function addBcc($address, $name = '')
    {
        $this->bcc[] = [$address, $name];
    }

    /**
     * Add a reply-to address to the email
     * @param string $address Email address
     * @param string $name Recipient name
     */
    public function addReplyTo($address, $name = '')
    {
        $this->reply_to[] = [$address, $name];
    }

    /**
     * Set the login credentials for the SMTP server
     * @param string $username SMTP username
     * @param string $password SMTP password
     */
    public function setLogin($username, $password)
    {
        $this->username = $username;
        $this->password = $password;
    }

    /**
     * Set the charset for the email
     * @param string $charset Charset (e.g., 'utf-8')
     */
    public function setCharset($charset)
    {
        $this->charset = $charset;
    }

    /**
     * Set the protocol for the SMTP connection
     * @param string $protocol Protocol (TLS, SSL, or empty for plain TCP)
     */
    public function setProtocol($protocol = '')
    {
        if (strtoupper($protocol) === 'TLS') {
            $this->tls = true;
            $this->protocol = 'tcp';
        } else {
            $this->protocol = strtolower($protocol);
        }
    }

    /**
     * Set the from address for the email
     * @param string $address Email address
     * @param string $name Sender name
     */
    public function setFrom($address, $name = '')
    {
        $this->from = [$address, $name];
    }

    /**
     * Set the subject of the email
     * @param string $subject Email subject
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
    }

    /**
     * Set the plain text message for the email
     * @param string $message Plain text message
     */
    public function setText($message)
    {
        $this->message_text = $message;
    }

    /**
     * Set the HTML message for the email
     * @param string $message HTML message
     */
    public function setHTML($message)
    {
        $this->message_html = $message;
    }

    /**
     * Add an attachment to the email
     * @param string $path Path to the file
     * @param string $name Name of the file (optional)
     */
    public function addAttachment($path, $name = '')
    {
        $this->attachments[] = [
            'path' => $path,
            'name' => $name ?: basename($path)
        ];
    }

    /**
     * Send the email
     * @return bool True if the email was sent successfully, false otherwise
     */
    public function send()
    {
        try {
            $this->socket = stream_socket_client(
                $this->getServerAddress(),
                $errno,
                $errstr,
                $this->connect_timeout,
                STREAM_CLIENT_CONNECT,
                $this->createContext()
            );

            if (!$this->socket) {
                throw new Exception("Connection failed: $errstr ($errno)");
            }

            $this->logResponse('CONNECT');

            $this->ehlo();

            if ($this->tls) {
                $this->startTLS();
                $this->ehlo();
            }

            $this->authenticate();
            $this->setMailFrom();
            $this->setRecipients();
            $this->sendData();
            $this->quit();

            fclose($this->socket);
            $this->writeLog();
            return true;
        } catch (Exception $e) {
            $this->logError($e->getMessage());
            $this->writeLog();
            if ($this->socket) {
                fclose($this->socket);
            }
            return false;
        }
    }

    /**
     * Send the EHLO command to the SMTP server
     */
    protected function ehlo()
    {
        $this->sendCommand("EHLO {$this->localhost}");
        $this->logResponse('EHLO');
    }

    /**
     * Start a TLS encrypted session
     */
    protected function startTLS()
    {
        $this->sendCommand('STARTTLS');
        $this->logResponse('STARTTLS');

        if (!stream_socket_enable_crypto($this->socket, true, STREAM_CRYPTO_METHOD_TLS_CLIENT)) {
            throw new Exception("TLS handshake failed");
        }
    }

    /**
     * Authenticate with the SMTP server
     */
    protected function authenticate()
    {
        $this->sendCommand('AUTH LOGIN');
        $this->logResponse('AUTH');

        $this->sendCommand(base64_encode($this->username));
        $this->logResponse('USERNAME');

        $this->sendCommand(base64_encode($this->password));
        $this->logResponse('PASSWORD');
    }

    /**
     * Set the MAIL FROM command
     */
    protected function setMailFrom()
    {
        $this->sendCommand("MAIL FROM: <{$this->from[0]}>");
        $this->logResponse('MAIL_FROM');
    }

    /**
     * Set the RCPT TO command for all recipients
     */
    protected function setRecipients()
    {
        $recipients = array_merge($this->to, $this->cc, $this->bcc);
        foreach ($recipients as $address) {
            $this->sendCommand("RCPT TO: <{$address[0]}>");
            $this->logResponse('RCPT_TO');
        }
    }

    /**
     * Send the email data
     */
    protected function sendData()
    {
        $this->sendCommand('DATA');
        $this->logResponse('DATA_INIT');

        $data = $this->buildHeaders() . self::CRLF . $this->buildBody();
        $this->sendCommand($data . self::CRLF . '.');
        $this->logResponse('DATA_SEND');
    }

    /**
     * Send the QUIT command to the SMTP server
     */
    protected function quit()
    {
        $this->sendCommand('QUIT');
        $this->logResponse('QUIT');
    }

    /**
     * Build the email headers
     * @return string The headers as a string
     */
    protected function buildHeaders()
    {
        $this->prepareHeaders();
        $headers = '';
        foreach ($this->headers as $key => $value) {
            $headers .= "$key: $value" . self::CRLF;
        }
        return rtrim($headers);
    }

    /**
     * Prepare the email headers
     */
    protected function prepareHeaders()
    {
        $this->headers['From'] = $this->formatAddress($this->from);
        $this->headers['To'] = $this->formatAddressList($this->to);

        if (!empty($this->cc)) {
            $this->headers['Cc'] = $this->formatAddressList($this->cc);
        }

        if (!empty($this->reply_to)) {
            $this->headers['Reply-To'] = $this->formatAddressList($this->reply_to);
        }

        $this->headers['Subject'] = $this->encodeHeader($this->subject);
        $this->headers['Date'] = date('r');

        if (!empty($this->attachments)) {
            $this->headers['Content-Type'] = "multipart/mixed; boundary=\"{$this->boundary}\"";
        } else {
            $this->headers['Content-Type'] = $this->message_html ? "text/html; charset={$this->charset}" : "text/plain; charset={$this->charset}";
        }
    }

    /**
     * Build the email body
     * @return string The email body as a string
     */
    protected function buildBody()
    {
        $body = '';

        if (!empty($this->attachments)) {
            $body .= "--{$this->boundary}" . self::CRLF;
            $body .= "Content-Type: multipart/alternative; boundary=\"{$this->boundary}_alt\"" . self::CRLF . self::CRLF;

            if ($this->message_html) {
                $body .= "--{$this->boundary}_alt" . self::CRLF;
                $body .= $this->encodeContent($this->message_text, 'text');
                $body .= "--{$this->boundary}_alt" . self::CRLF;
                $body .= $this->encodeContent($this->message_html, 'html');
                $body .= "--{$this->boundary}_alt--" . self::CRLF;
            } else {
                $body .= "--{$this->boundary}_alt" . self::CRLF;
                $body .= $this->encodeContent($this->message_text, 'text');
                $body .= "--{$this->boundary}_alt--" . self::CRLF;
            }

            foreach ($this->attachments as $attachment) {
                $body .= "--{$this->boundary}" . self::CRLF;
                $body .= "Content-Type: application/octet-stream; name=\"{$attachment['name']}\"" . self::CRLF;
                $body .= "Content-Transfer-Encoding: base64" . self::CRLF;
                $body .= "Content-Disposition: attachment; filename=\"{$attachment['name']}\"" . self::CRLF . self::CRLF;
                $body .= chunk_split(base64_encode(file_get_contents($attachment['path']))) . self::CRLF;
            }

            $body .= "--{$this->boundary}--" . self::CRLF;
        } else {
            $body .= $this->encodeContent($this->message_html ?: $this->message_text, $this->message_html ? 'html' : 'text');
        }

        return $body;
    }

    /**
     * Encode the email content
     * @param string $content The content to encode
     * @param string $type The content type ('text' or 'html')
     * @return string The encoded content
     */
    protected function encodeContent($content, $type)
    {
        $contentType = $type === 'html'
            ? "text/html; charset={$this->charset}"
            : "text/plain; charset={$this->charset}";

        return "Content-Type: $contentType" . self::CRLF
            . "Content-Transfer-Encoding: base64" . self::CRLF . self::CRLF
            . chunk_split(base64_encode($content)) . self::CRLF;
    }

    /**
     * Get the server address with the appropriate protocol prefix
     * @return string The server address
     */
    protected function getServerAddress()
    {
        $prefix = $this->protocol === self::SSL ? 'ssl://' : 'tcp://';
        return $prefix . $this->server . ':' . $this->port;
    }

    /**
     * Create a stream context for the connection
     * @return resource The stream context
     */
    protected function createContext()
    {
        return stream_context_create([
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            ]
        ]);
    }

    /**
     * Send a command to the SMTP server
     * @param string $command The command to send
     */
    protected function sendCommand($command)
    {
        fwrite($this->socket, $command . self::CRLF);
    }

    /**
     * Log the response from the SMTP server
     * @param string $key The key to identify the response
     */
    protected function logResponse($key)
    {
        $response = '';
        while ($line = fgets($this->socket, 515)) {
            $response .= $line;
            if (substr($line, 3, 1) === ' ') break;
        }
        $this->log[$key][] = rtrim($response);
    }

    /**
     * Format an email address
     * @param array $address The email address and name
     * @return string The formatted email address
     */
    protected function formatAddress($address)
    {
        if (!empty($address[1])) {
            $address[1] = $this->encodeHeader($address[1]);
        }
        return empty($address[1])
            ? $address[0]
            : "{$address[1]} <{$address[0]}>";
    }

    /**
     * Format a list of email addresses
     * @param array $addresses The list of email addresses
     * @return string The formatted list of email addresses
     */
    protected function formatAddressList($addresses)
    {
        return implode(', ', array_map([$this, 'formatAddress'], $addresses));
    }

    /**
     * Encode a header value according to RFC 2047
     * @param string $value The header value to encode
     * @return string The encoded header value
     */
    protected function encodeHeader($string)
    {
        return '=?' . $this->charset . '?B?' . base64_encode($string) . '?=';
    }

    /**
     * Log an error message
     * @param string $message The error message
     */
    protected function logError($message)
    {
        $this->log['ERROR'][] = $message;
    }

    /**
     * Write the log to the log file
     */
    protected function writeLog()
    {
        $logEntry = "[" . date('Y-m-d H:i:s') . "] Email Log\n";
        foreach ($this->log as $key => $entries) {
            $logEntry .= "$key:\n" . implode("\n", $entries) . "\n";
        }
        $logEntry .= str_repeat('-', 40) . "\n";
        file_put_contents($this->logFile, $logEntry, FILE_APPEND);
    }
}
