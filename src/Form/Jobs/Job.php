<?php
namespace Gastro24\Form\Jobs;

use Core\Form\Container;
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
        /** @var Container $generalForm */
        $generalForm = $this->get('general');
        $generalForm->disableForm('salaryForm');
        $generalForm->disableForm('customerNote');

        $generalForm->setForm('categoryForm', [
            'type' => 'Gastro24\Form\Jobs\CategoryForm',
            'property' => true,
            'priority' => 30,
            'options' => array(
                'enable_descriptions' => true,
                'description' => /*@translate*/ 'Choose a category for the job.',
                'display_mode' => 'summary'
            )
        ]);

        $generalForm->get('nameForm')->setLabel('Firmenprofil');
        $generalForm->get('nameForm')->get('jobCompanyName')->get('companyId')->setLabel('Firmenprofil auswählen');

        // WORKAROUND: reorder subforms
        $generalForm->forms['locationForm']['priority'] = 50;
        $generalForm->forms['nameForm']['priority'] = 40;
        $generalForm->forms['classifications']['priority'] = 20;
        $generalForm->forms['portalForm']['priority'] = 10;
    }

    public function renderPost(Renderer $renderer)
    {
        $coreformsjs   = $renderer->basepath('modules/Core/js/core.forms.js');
        $javaScript = <<<JS
        $(document).ready(function() {
            
            var Select2Cascade = ( function(window, $) {
                function Select2Cascade(parent, child, url, select2Options) {
                    var afterActions = [];
                    var options = select2Options || {};

                    // Register functions to be called after cascading data loading done
                    this.then = function(callback) {
                        afterActions.push(callback);
                        return this;
                    };
                    
                    parent.on("change", function (e) {
                        child.prop("disabled", true);
                        child.empty();
                        var _this = this;
                        $.getJSON(url.replace(':parentId:', $(this).val()), function(items) {
                            var option = new Option('-- keine Auswahl --', null, false, false);
                            child.append(option);
                            for(var id in items) {
                                var option = new Option(items[id], id, false, false);
                                child.append(option).trigger('change');
                            }
                            child.prop("disabled", false).select2(options);

                            afterActions.forEach(function (callback) {
                                callback(parent, child, items);
                            });
                        });
                    });
                }

                return Select2Cascade;

            })( window, $);

            var select2Options = { 
                theme: 'bootstrap',
                minimumResultsForSearch: 1
            };
            var apiUrl =  '/' + lang + '/landingpage/:parentId:/childs';
            new Select2Cascade($('#category'), $('#subcategory'), apiUrl, select2Options);

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