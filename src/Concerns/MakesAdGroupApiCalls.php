<?php

declare(strict_types=1);

namespace PowerSrc\AmazonAdvertisingApi\Concerns;

use PowerSrc\AmazonAdvertisingApi\Enums\HttpMethod;
use PowerSrc\AmazonAdvertisingApi\Enums\MimeType;
use PowerSrc\AmazonAdvertisingApi\Exceptions\ClassNotFoundException;
use PowerSrc\AmazonAdvertisingApi\Exceptions\HttpException;
use PowerSrc\AmazonAdvertisingApi\Models\AdGroup;
use PowerSrc\AmazonAdvertisingApi\Models\AdGroupBidRecommendation;
use PowerSrc\AmazonAdvertisingApi\Models\AdGroupEx;
use PowerSrc\AmazonAdvertisingApi\Models\AdGroupResponse;
use PowerSrc\AmazonAdvertisingApi\Models\Lists\AdGroup\AdGroupCreateList;
use PowerSrc\AmazonAdvertisingApi\Models\Lists\AdGroup\AdGroupExList;
use PowerSrc\AmazonAdvertisingApi\Models\Lists\AdGroup\AdGroupList;
use PowerSrc\AmazonAdvertisingApi\Models\Lists\AdGroup\AdGroupResponseList;
use PowerSrc\AmazonAdvertisingApi\Models\Lists\AdGroup\AdGroupUpdateList;
use PowerSrc\AmazonAdvertisingApi\Models\RequestParams\AdGroupParams;
use ReflectionException;

trait MakesAdGroupApiCalls
{
    /**
     * Retrieves an ad group by ID.
     *
     * Note that this call returns the minimal set of ad group fields,
     * but is more efficient than getAdGroupEx.
     *
     * @param int $adGroupId
     *
     * @throws ClassNotFoundException
     * @throws HttpException
     * @throws ReflectionException
     *
     * @return AdGroup
     */
    public function getAdGroup(int $adGroupId): AdGroup
    {
        $response = $this->operation(HttpMethod::GET(), $this->getApiUrl('sp/adGroups/' . $adGroupId));

        return new AdGroup($this->decodeResponseBody($response, MimeType::JSON()));
    }

    /**
     * Retrieves an ad group and its extended fields by ID.
     *
     * Note that this call returns the complete set of ad group fields
     * (including serving status and other read-only fields),
     * but is less efficient than getAdGroup.
     *
     * @param int $adGroupId
     *
     * @throws ClassNotFoundException
     * @throws HttpException
     * @throws ReflectionException
     *
     * @return AdGroupEx
     */
    public function getAdGroupEx(int $adGroupId): AdGroupEx
    {
        $response = $this->operation(HttpMethod::GET(), $this->getApiUrl('sp/adGroups/extended/' . $adGroupId));

        return new AdGroupEx($this->decodeResponseBody($response, MimeType::JSON()));
    }

    /**
     * Creates one or more ad groups. Successfully created ad groups will be assigned a unique adGroupId.
     *
     * @param AdGroupCreateList $adGroupCreateList
     *
     * @throws ClassNotFoundException
     * @throws HttpException
     *
     * @return AdGroupResponseList
     */
    public function createAdGroups(AdGroupCreateList $adGroupCreateList): AdGroupResponseList
    {
        $response = $this->operation(HttpMethod::POST(), $this->getApiUrl('sp/adGroups'), $adGroupCreateList);

        return new AdGroupResponseList($this->decodeResponseBody($response, MimeType::JSON()));
    }

    /**
     * Updates one or more ad groups. Ad groups are identified using their adGroupId.
     *
     * @param AdGroupUpdateList $adGroupUpdateList
     *
     * @throws ClassNotFoundException
     * @throws HttpException
     *
     * @return AdGroupResponseList
     */
    public function updateAdGroups(AdGroupUpdateList $adGroupUpdateList): AdGroupResponseList
    {
        $response = $this->operation(HttpMethod::PUT(), $this->getApiUrl('sp/adGroups'), $adGroupUpdateList);

        return new AdGroupResponseList($this->decodeResponseBody($response, MimeType::JSON()));
    }

    /**
     * Sets the ad group status to archived.
     *
     * This same operation can be performed via an update, but is included for completeness.
     * Archived entities cannot be made active again.
     *
     * @param int $adGroupId
     *
     * @throws ClassNotFoundException
     * @throws HttpException
     * @throws ReflectionException
     *
     * @return AdGroupResponse
     */
    public function archiveAdGroup(int $adGroupId): AdGroupResponse
    {
        $response = $this->operation(HttpMethod::DELETE(), $this->getApiUrl('sp/adGroups/' . $adGroupId));

        return new AdGroupResponse($this->decodeResponseBody($response, MimeType::JSON()));
    }

    /**
     * Retrieves a list of ad groups satisfying optional criteria.
     *
     * @param AdGroupParams $params
     *
     * @throws ClassNotFoundException
     * @throws HttpException
     *
     * @return AdGroupList
     */
    public function listAdGroups(AdGroupParams $params): AdGroupList
    {
        $response = $this->operation(HttpMethod::GET(), $this->getApiUrl('sp/adGroups', $params));

        return new AdGroupList($this->decodeResponseBody($response, MimeType::JSON()));
    }

    /**
     * Retrieves a list of ad groups with extended fields satisfying optional filtering criteria.
     *
     * @param AdGroupParams $params
     *
     * @throws ClassNotFoundException
     * @throws HttpException
     *
     * @return AdGroupExList
     */
    public function listAdGroupsEx(AdGroupParams $params): AdGroupExList
    {
        $response = $this->operation(HttpMethod::GET(), $this->getApiUrl('sp/adGroups/extended', $params));

        return new AdGroupExList($this->decodeResponseBody($response, MimeType::JSON()));
    }

    /**
     * Retrieve bid recommendation data for the specified adGroupId.
     *
     * @param int $adGroupId
     *
     * @throws ClassNotFoundException
     * @throws HttpException
     * @throws ReflectionException
     *
     * @return AdGroupBidRecommendation
     */
    public function getAdGroupBidRecommendations(int $adGroupId): AdGroupBidRecommendation
    {
        $response = $this->operation(HttpMethod::GET(), $this->getApiUrl('adGroups/' . $adGroupId . '/bidRecommendations'));

        return new AdGroupBidRecommendation($this->decodeResponseBody($response, MimeType::JSON()));
    }
}
