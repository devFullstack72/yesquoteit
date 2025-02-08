jQuery(document).ready(function ($) {
    let currentStep = 0;
    const steps = $('.step');
    const totalSteps = steps.length;
  
    function showStep(step) {
      steps.hide();
      $(steps[step]).show();
      $('.prev-step').toggle(step > 0);
      $('.next-step').toggle(step < totalSteps - 1);
      $('.submit-form').toggle(step === totalSteps - 1);
    }
  
    showStep(currentStep);
  
    $('.next-step').click(function () {
      if (validateCurrentStep()) {
        currentStep++;
        showStep(currentStep);
      }
    });
  
    $('.prev-step').click(function () {
      currentStep--;
      showStep(currentStep);
    });
  
    $('#cf7WizardForm').submit(function (e) {
      e.preventDefault();
      alert('Form submitted!');
    });
  
    function validateCurrentStep() {
      let $currentStep = $(".step:visible");
      let $inputs = $currentStep.find("input, textarea, select");
      let allValid = true;
  
      $inputs.each(function () {
        let $input = $(this);
        if ($input.data("required") === true && $.trim($input.val()) === "") {
          allValid = false;
          $input.addClass("form-control-error");
        } else {
          $input.removeClass("form-control-error");
        }
      });
  
      return allValid;
    }
  });
  