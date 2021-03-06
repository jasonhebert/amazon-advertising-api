<?php

declare(strict_types=1);

namespace PowerSrc\AmazonAdvertisingApi\Concerns;

use PowerSrc\AmazonAdvertisingApi\Enums\HttpMethod;
use PowerSrc\AmazonAdvertisingApi\Enums\MimeType;
use PowerSrc\AmazonAdvertisingApi\Enums\ReportRecordType;
use PowerSrc\AmazonAdvertisingApi\Exceptions\ClassNotFoundException;
use PowerSrc\AmazonAdvertisingApi\Exceptions\HttpException;
use PowerSrc\AmazonAdvertisingApi\Exceptions\ReportGZDecodeError;
use PowerSrc\AmazonAdvertisingApi\Models\ReportResponse;
use PowerSrc\AmazonAdvertisingApi\Models\Reports\AdGroupReport;
use PowerSrc\AmazonAdvertisingApi\Models\Reports\AsinReport;
use PowerSrc\AmazonAdvertisingApi\Models\Reports\CampaignReport;
use PowerSrc\AmazonAdvertisingApi\Models\Reports\KeywordReport;
use PowerSrc\AmazonAdvertisingApi\Models\Reports\ProductAdReport;
use PowerSrc\AmazonAdvertisingApi\Models\Reports\TargetReport;
use PowerSrc\AmazonAdvertisingApi\Models\RequestParams\AdGroupReportParams;
use PowerSrc\AmazonAdvertisingApi\Models\RequestParams\AsinReportParams;
use PowerSrc\AmazonAdvertisingApi\Models\RequestParams\CampaignReportParams;
use PowerSrc\AmazonAdvertisingApi\Models\RequestParams\KeywordReportParams;
use PowerSrc\AmazonAdvertisingApi\Models\RequestParams\ProductAdReportParams;
use PowerSrc\AmazonAdvertisingApi\Models\RequestParams\ReportParams;
use PowerSrc\AmazonAdvertisingApi\Models\RequestParams\TargetReportParams;
use ReflectionException;

trait MakesReportCalls
{
    /**
     * @param ReportRecordType $type
     * @param ReportParams     $params
     *
     * @throws ClassNotFoundException
     * @throws HttpException
     * @throws ReflectionException
     *
     * @return ReportResponse
     */
    public function requestReport(ReportRecordType $type, ReportParams $params): ReportResponse
    {
        $response = $this->operation(HttpMethod::POST(), $this->getApiUrl('sp/' . $type->getValue() . '/report'), $params);

        return new ReportResponse($this->decodeResponseBody($response, MimeType::JSON()));
    }

    /**
     * @param string $reportId
     *
     * @throws ClassNotFoundException
     * @throws HttpException
     * @throws ReflectionException
     *
     * @return ReportResponse
     */
    public function getReport(string $reportId)
    {
        $response = $this->operation(HttpMethod::GET(), $this->getApiUrl('reports/' . $reportId));

        return new ReportResponse($this->decodeResponseBody($response, MimeType::JSON()));
    }

    /**
     * Downloads the report file at location provided and returns
     * the decoded payload.
     *
     * @param string $location
     *
     * @throws ReportGZDecodeError
     * @throws HttpException
     *
     * @return mixed
     */
    public function downloadReport(string $location)
    {
        $response = $this->operation(HttpMethod::GET(), $location);

        return $this->decodeReport($response, $location);
    }

    /**
     * @param CampaignReportParams $params
     *
     * @throws ClassNotFoundException
     * @throws HttpException
     * @throws ReflectionException
     *
     * @return ReportResponse
     */
    public function requestCampaignsReport(CampaignReportParams $params)
    {
        return $this->requestReport(ReportRecordType::CAMPAIGNS(), $params);
    }

    /**
     * @param AdGroupReportParams $params
     *
     * @throws ClassNotFoundException
     * @throws HttpException
     * @throws ReflectionException
     *
     * @return ReportResponse
     */
    public function requestAdGroupsReport(AdGroupReportParams $params)
    {
        return $this->requestReport(ReportRecordType::AD_GROUPS(), $params);
    }

    /**
     * @param ProductAdReportParams $params
     *
     * @throws ClassNotFoundException
     * @throws HttpException
     * @throws ReflectionException
     *
     * @return ReportResponse
     */
    public function requestProductAdsReport(ProductAdReportParams $params)
    {
        return $this->requestReport(ReportRecordType::PRODUCT_ADS(), $params);
    }

    /**
     * @param KeywordReportParams $params
     *
     * @throws ClassNotFoundException
     * @throws HttpException
     * @throws ReflectionException
     *
     * @return ReportResponse
     */
    public function requestKeywordsReport(KeywordReportParams $params)
    {
        return $this->requestReport(ReportRecordType::KEYWORDS(), $params);
    }

    /**
     * @param TargetReportParams $params
     *
     * @throws ClassNotFoundException
     * @throws HttpException
     * @throws ReflectionException
     *
     * @return ReportResponse
     */
    public function requestTargetsReport(TargetReportParams $params)
    {
        return $this->requestReport(ReportRecordType::TARGETS(), $params);
    }

    /**
     * @param AsinReportParams $params
     *
     * @throws ClassNotFoundException
     * @throws HttpException
     * @throws ReflectionException
     *
     * @return ReportResponse
     */
    public function requestAsinsReport(AsinReportParams $params)
    {
        return $this->requestReport(ReportRecordType::ASINS(), $params);
    }

    /**
     * @param string $location
     *
     *@throws HttpException
     * @throws ReportGZDecodeError
     * @throws ClassNotFoundException
     *
     * @return \PowerSrc\AmazonAdvertisingApi\Models\Reports\CampaignReport
     */
    public function downloadCampaignsReport(string $location): CampaignReport
    {
        return new CampaignReport($this->downloadReport($location));
    }

    /**
     * @param string $location
     *
     *@throws HttpException
     * @throws ReportGZDecodeError
     * @throws ClassNotFoundException
     *
     * @return AdGroupReport
     */
    public function downloadAdGroupsReport(string $location): AdGroupReport
    {
        return new AdGroupReport($this->downloadReport($location));
    }

    /**
     * @param string $location
     *
     *@throws HttpException
     * @throws ReportGZDecodeError
     * @throws ClassNotFoundException
     *
     * @return AsinReport
     */
    public function downloadAsinsReport(string $location): AsinReport
    {
        return new AsinReport($this->downloadReport($location));
    }

    /**
     * @param string $location
     *
     *@throws HttpException
     * @throws ReportGZDecodeError
     * @throws ClassNotFoundException
     *
     * @return KeywordReport
     */
    public function downloadKeywordsReport(string $location): KeywordReport
    {
        return new KeywordReport($this->downloadReport($location));
    }

    /**
     * @param string $location
     *
     *@throws HttpException
     * @throws ReportGZDecodeError
     * @throws ClassNotFoundException
     *
     * @return \PowerSrc\AmazonAdvertisingApi\Models\Reports\ProductAdReport
     */
    public function downloadProductAdsReport(string $location): ProductAdReport
    {
        return new ProductAdReport($this->downloadReport($location));
    }

    /**
     * @param string $location
     *
     *@throws HttpException
     * @throws ReportGZDecodeError
     * @throws ClassNotFoundException
     *
     * @return TargetReport
     */
    public function downloadTargetsReport(string $location): TargetReport
    {
        return new TargetReport($this->downloadReport($location));
    }
}
