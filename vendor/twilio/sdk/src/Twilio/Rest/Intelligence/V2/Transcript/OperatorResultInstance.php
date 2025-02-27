<?php

/**
 * This code was generated by
 * ___ _ _ _ _ _    _ ____    ____ ____ _    ____ ____ _  _ ____ ____ ____ ___ __   __
 *  |  | | | | |    | |  | __ |  | |__| | __ | __ |___ |\ | |___ |__/ |__|  | |  | |__/
 *  |  |_|_| | |___ | |__|    |__| |  | |    |__] |___ | \| |___ |  \ |  |  | |__| |  \
 *
 * Twilio - Intelligence
 * This is the public Twilio REST API.
 *
 * NOTE: This class is auto generated by OpenAPI Generator.
 * https://openapi-generator.tech
 * Do not edit the class manually.
 */


namespace Twilio\Rest\Intelligence\V2\Transcript;

use Twilio\Exceptions\TwilioException;
use Twilio\InstanceResource;
use Twilio\Options;
use Twilio\Values;
use Twilio\Version;


/**
 * @property string $operatorType
 * @property string|null $name
 * @property string|null $operatorSid
 * @property bool|null $extractMatch
 * @property string|null $matchProbability
 * @property string|null $normalizedResult
 * @property array[]|null $utteranceResults
 * @property bool|null $utteranceMatch
 * @property string|null $predictedLabel
 * @property string|null $predictedProbability
 * @property array|null $labelProbabilities
 * @property array|null $extractResults
 * @property array|null $textGenerationResults
 * @property array|null $jsonResults
 * @property string|null $transcriptSid
 * @property string|null $url
 */
class OperatorResultInstance extends InstanceResource
{
    /**
     * Initialize the OperatorResultInstance
     *
     * @param Version $version Version that contains the resource
     * @param mixed[] $payload The response payload
     * @param string $transcriptSid A 34 character string that uniquely identifies this Transcript.
     * @param string $operatorSid A 34 character string that identifies this Language Understanding operator sid.
     */
    public function __construct(Version $version, array $payload, string $transcriptSid, string $operatorSid = null)
    {
        parent::__construct($version);

        // Marshaled Properties
        $this->properties = [
            'operatorType' => Values::array_get($payload, 'operator_type'),
            'name' => Values::array_get($payload, 'name'),
            'operatorSid' => Values::array_get($payload, 'operator_sid'),
            'extractMatch' => Values::array_get($payload, 'extract_match'),
            'matchProbability' => Values::array_get($payload, 'match_probability'),
            'normalizedResult' => Values::array_get($payload, 'normalized_result'),
            'utteranceResults' => Values::array_get($payload, 'utterance_results'),
            'utteranceMatch' => Values::array_get($payload, 'utterance_match'),
            'predictedLabel' => Values::array_get($payload, 'predicted_label'),
            'predictedProbability' => Values::array_get($payload, 'predicted_probability'),
            'labelProbabilities' => Values::array_get($payload, 'label_probabilities'),
            'extractResults' => Values::array_get($payload, 'extract_results'),
            'textGenerationResults' => Values::array_get($payload, 'text_generation_results'),
            'jsonResults' => Values::array_get($payload, 'json_results'),
            'transcriptSid' => Values::array_get($payload, 'transcript_sid'),
            'url' => Values::array_get($payload, 'url'),
        ];

        $this->solution = ['transcriptSid' => $transcriptSid, 'operatorSid' => $operatorSid ?: $this->properties['operatorSid'], ];
    }

    /**
     * Generate an instance context for the instance, the context is capable of
     * performing various actions.  All instance actions are proxied to the context
     *
     * @return OperatorResultContext Context for this OperatorResultInstance
     */
    protected function proxy(): OperatorResultContext
    {
        if (!$this->context) {
            $this->context = new OperatorResultContext(
                $this->version,
                $this->solution['transcriptSid'],
                $this->solution['operatorSid']
            );
        }

        return $this->context;
    }

    /**
     * Fetch the OperatorResultInstance
     *
     * @param array|Options $options Optional Arguments
     * @return OperatorResultInstance Fetched OperatorResultInstance
     * @throws TwilioException When an HTTP error occurs.
     */
    public function fetch(array $options = []): OperatorResultInstance
    {

        return $this->proxy()->fetch($options);
    }

    /**
     * Magic getter to access properties
     *
     * @param string $name Property to access
     * @return mixed The requested property
     * @throws TwilioException For unknown properties
     */
    public function __get(string $name)
    {
        if (\array_key_exists($name, $this->properties)) {
            return $this->properties[$name];
        }

        if (\property_exists($this, '_' . $name)) {
            $method = 'get' . \ucfirst($name);
            return $this->$method();
        }

        throw new TwilioException('Unknown property: ' . $name);
    }

    /**
     * Provide a friendly representation
     *
     * @return string Machine friendly representation
     */
    public function __toString(): string
    {
        $context = [];
        foreach ($this->solution as $key => $value) {
            $context[] = "$key=$value";
        }
        return '[Twilio.Intelligence.V2.OperatorResultInstance ' . \implode(' ', $context) . ']';
    }
}

