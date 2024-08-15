<?php

namespace App\Validator;

use App\Entity\Ad;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

/**
 * Contains validation conditions for <code>App\Entity\Ad</code>'s fields.
 *
 * <pre>
 *          ...
 *          $validateResult = $validator->validate($ad);
 * </pre>
 *
 * @see AdFields
 *
 * @since   0.0.1
 *
 * @author  WorHyako
 */
class AdFieldsValidator extends ConstraintValidator
{
    private int $minPrice = 1;
    private int $minBannerLength = 1;
    private int $minTextLength = 1;
    private int $minLimit = 1;

    /**
     * Validate object
     *
     * @param mixed|Ad $value Object to validate.
     *
     * @param Constraint|AdFields $constraint Constraint with validation messages.
     *
     * @return void Parent's method will return a list of constraint violations.
     *              <p/>
     *              If the list is empty, validation succeeded.
     *
     * @see AdFields
     *
     * @see Ad
     */
    public function validate(mixed $value, Constraint|AdFields $constraint): void
    {
        if (!$value instanceof Ad) {
            throw new UnexpectedValueException($value, Ad::class);
        }

        if (!$constraint instanceof AdFields) {
            throw new UnexpectedValueException($value, AdFields::class);
        }

        if ($value->getPrice() == null || $value->getPrice() < $this->minPrice) {
            $this->context->buildViolation($constraint->incorrectPriceMessage)
                ->atPath('price')
                ->addViolation();
        }

        if ($value->getShowLimit() == null || $value->getShowLimit() < $this->minLimit) {
            $this->context->buildViolation($constraint->incorrectShowLimitMessage)
                ->atPath('limit')
                ->addViolation();
        }

        if ($value->getBanner() == null || strlen($value->getBanner()) < $this->minBannerLength) {
            $this->context->buildViolation($constraint->incorrectBannerMessage)
                ->atPath('banner')
                ->addViolation();
        }

        if ($value->getText() == null || strlen($value->getText()) < $this->minTextLength) {
            $this->context->buildViolation($constraint->incorrectTextMessage)
                ->atPath('text')
                ->addViolation();
        }
    }
}
