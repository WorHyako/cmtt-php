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

        if ($value->getPrice() == null || $value->getPrice() < Ad::$minPrice) {
            $this->context->buildViolation($constraint->incorrectPriceMessage)
                ->atPath('price')
                ->addViolation();
        }

        if ($value->getShowCount() < 0) {
            $this->context->buildViolation($constraint->incorrectShowCountMessage)
                ->atPath('showCount')
                ->addViolation();
        }

        if ($value->getBanner() == null || strlen($value->getBanner()) < Ad::$minBannerLength) {
            $this->context->buildViolation($constraint->incorrectBannerMessage)
                ->atPath('banner')
                ->addViolation();
        }

        if ($value->getText() == null || strlen($value->getText()) < Ad::$minTextLength) {
            $this->context->buildViolation($constraint->incorrectTextMessage)
                ->atPath('text')
                ->addViolation();
        }
    }
}
