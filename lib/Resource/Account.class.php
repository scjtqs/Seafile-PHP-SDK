<?php

namespace Seafile\Client\Resource;

use Seafile\Client\Type\AbstractType;
use \Seafile\Client\Type\Account as AccountType;

/**
 * Handles everything regarding Seafile accounts.
 *
 * PHP version 5
 *
 * @category  API
 * @package   Seafile\Resource
 * @author    Rene Schmidt DevOps UG (haftungsbeschränkt) & Co. KG <rene@reneschmidt.de>
 * @copyright 2015 Rene Schmidt DevOps UG (haftungsbeschränkt) & Co. KG <rene@reneschmidt.de>
 * @license   https://opensource.org/licenses/MIT MIT
 * @link      https://github.com/rene-s/seafile-php-sdk
 */
class Account extends AbstractResource
{
    /**
     * List accounts
     *
     * Requires admin permissions
     *
     * @return AccountType[]
     */
    public function getAll()
    {
        $response = $this->client->request('GET', $this->client->getConfig('base_uri') . '/accounts/');

        $json = json_decode($response->getBody());

        $libCollection = [];

        foreach ($json as $lib) {
            $libCollection[] = (new AccountType)->fromJson($lib);
        }

        return $libCollection;
    }

    /**
     * Get AccountType instance by email address
     *
     * Requires admin permissions
     *
     * @param string $emailAddress Email address
     *
     * @return AccountType
     */
    public function getByEmail($emailAddress)
    {
        $response = $this->client->request(
            'GET',
            // $emailAddress must not be urlencoded
            $this->client->getConfig('base_uri') . '/accounts/' . $emailAddress . '/'
        );

        $json = json_decode($response->getBody());

        return (new AccountType)->fromJson($json);
    }

    /**
     * Get Account info
     *
     * @return AccountType
     */
    public function getInfo()
    {
        $response = $this->client->request(
            'GET',
            $this->client->getConfig('base_uri') . '/account/info/'
        );

        $json = json_decode($response->getBody());

        return (new AccountType)->fromJson($json);
    }

    /**
     * Create a new account
     *
     * Requires admin permissions
     *
     * @param AccountType $accountType AccountType instance with data for new account
     *
     * @return bool
     */
    public function create(AccountType $accountType)
    {
        // Password and email are required
        if (empty($accountType->password) || empty($accountType->email)) {
            return false;
        }

        $uri = sprintf(
            '%s/accounts/' . $accountType->email . '/',
            $this->clipUri($this->client->getConfig('base_uri'))
        );

        $response = $this->client->request(
            'PUT',
            $uri,
            [
                'headers' => ['Accept' => 'application/json; charset=utf-8'],
                'multipart' => $accountType->toArray(AbstractType::ARRAY_MULTI_PART),
            ]
        );

        return $response->getStatusCode() === 201;
    }

    /**
     * Update account
     *
     * @param AccountType $accountType AccountType instance with updated data
     *
     * @return bool
     */
    public function update(AccountType $accountType)
    {
        // Password and email are required
        if (empty($accountType->password) || empty($accountType->email)) {
            return false;
        }

        $uri = sprintf(
            '%s/accounts/' . $accountType->email . '/',
            $this->clipUri($this->client->getConfig('base_uri'))
        );

        $params = $accountType->toArray(MODE);

        $response = $this->client->request(
            'PUT',
            $uri,
            [
                'headers' => ['Accept' => 'application/json; charset=utf-8'],
                'params' => $params,
            ]
        );

        return $response->getStatusCode() === 200;
    }

    /**
     * Migrate account
     *
     * Requires admin permissions
     *
     * @param AccountType $fromAccountType AccountType instance to update from
     * @param AccountType $toAccountType   AccountType instance to update to
     *
     * @return bool
     * @todo Yet to be implemented
     */
    public function migrate(AccountType $fromAccountType, AccountType $toAccountType)
    {
        return false; // not yet implemented
    }

    /**
     * Remove a account by email
     *
     * Requires admin permissions
     *
     * @param string $email Email address
     *
     * @return bool
     */
    public function removeByEmail($email)
    {
        return $this->remove((new AccountType)->fromArray(['email' => $email]));
    }

    /**
     * Remove account
     *
     * Requires admin permissions
     *
     * @param AccountType $accountType Account to remove
     *
     * @return bool
     */
    public function remove(AccountType $accountType)
    {
        if (empty($accountType->email)) {
            return false;
        }

        $uri = sprintf(
            '%s/accounts/%s',
            $this->clipUri($this->client->getConfig('base_uri')),
            $accountType->email
        );

        return $this->client->delete($uri)->getStatusCode() === 200;
    }
}
