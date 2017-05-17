<?php

namespace Liuggio\StatsDClientBundle\StatsCollector;


class UserStatsCollector extends StatsCollector
{
    private $authorization_checker;

    /**
     * Collects data for the given Response.
     *
     * @param Request    $request   A Request instance
     * @param Response   $response  A Response instance
     * @param \Exception $exception An exception instance if the request threw one
     *
     * @return Boolean
     */
    public function collect(Request $request, Response $response, \Exception $exception = null)
    {

        if (null === $this->getSecurityContext()) {
            return true;
        }

        $key = sprintf('%s.anonymous', $this->getStatsDataKey());
        try {
            if ($this->getSecurityContext()->isGranted('IS_AUTHENTICATED_FULLY')) {
                $key = sprintf('%s.logged', $this->getStatsDataKey());
            }
        } catch (AuthenticationCredentialsNotFoundException $exception) {
            //do nothing
        }
        $statData = $this->getStatsdDataFactory()->increment($key);
        $this->addStatsData($statData);

        return true;
    }

    public function setSecurityAuthorizationChecker(Secu $authorization_checker)
    {
        $this->authorization_checker = $authorization_checker;
    }

    public function getSecurityContext()
    {
        return $this->authorization_checker;
    }


}
