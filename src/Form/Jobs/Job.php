<?php
namespace Gastro24\Form\Jobs;

use Core\Form\WizardContainer;
use Jobs\Form\Job as BaseJobForm;
use Laminas\View\Renderer\PhpRenderer as Renderer;


class Job extends BaseJobForm
{
    /**
     * {@inheritDoc}
     */
    public function init()
    {
        parent::init();
        $this->get('general')->disableForm('salaryForm');
        $this->get('general')->disableForm('customerNote');
        $this->get('general')->get('nameForm')->setLabel('Firmenprofil');
        $this->get('general')->get('nameForm')->get('jobCompanyName')->get('companyId')->setLabel('Firmenprofil auswählen');
    }

    public function renderPost(Renderer $renderer)
    {
        $coreformsjs   = $renderer->basepath('modules/Core/js/core.forms.js');
        $javaScript = <<<JS
        $(document).ready(function() {

            console.log('attached yk.forms.done to ', \$('form'));

             \$('form').on('yk.forms.done', function(event, data) {
                //if (typeof data != 'undefined' && typeof data['data'] != 'undefined') {}
                if (typeof data != 'undefined' && typeof data['data'] != 'undefined') {
                    if (typeof data['data']['jobvalid'] != 'undefined' && data['data']['jobvalid'] === true) {
                        $('#job_incomplete').hide();
                        \$('.wizard-container .finish').removeClass('disabled');
                    }
                    else {
                        $('#job_incomplete').show();
                        \$('.wizard-container .finish').addClass('disabled');
                    }
                }
                \$('#job_errormessages').empty();

                if (typeof data['data']['errorMessage'] != 'undefined') {
                    $('#job_errormessages').append(data['data']['errorMessage']);
                }
                console.debug('job-form-inline', event, data);
             });
             \$('.wizard-container').on('wizard:tabShow.jobcontainer', function(e, \$tab, \$nav, index) {
                var \$link = \$tab.find('a');
                var href = \$link.attr('href');
                var \$target = \$(href);
                var \$iframe = \$target.find('iframe');
                console.log('reload in template')

                \$iframe.each(function() { 
                    console.log('in each')
                    console.log($(this))
                    console.log(this.id)
                    if (this.id == 'details[position]_ifr') {
                        console.log('return');
                        return;
                    }
                    console.log('reload');
                    this.contentDocument.location.reload(true);
                });

                var \$productList = \$target.find('#product-list-wrapper');
                if (\$productList.length) {
                    \$productList.html('').load('/' + lang + '/jobs/channel-list?id=' + \$('#general-nameForm-job').val());
                }
             });

             \$('.wizard-container .finish a').click(function (e) {
                if (\$(e.currentTarget).parent().hasClass('disabled')) {
                    e.preventDefault();
                    return false;
                }
             });

        });
JS;

        $renderer->headScript()->appendScript($javaScript);

        return WizardContainer::renderPost($renderer);
    }

}