/* global jQuery */
jQuery(function ($) {

  // build array of organization for donations
  let arrayOfOrganizations = [];
  $('.woocommerce-input-wrapper label').each(function (index) {
    let content = JSON.parse($(this).text());
    arrayOfOrganizations.push(content);
  });

  // build html for the organizations
  let html = "<div class='donation-organization-container' style='display:none'>"
  for (let org of arrayOfOrganizations) {

    let tooltipButton = 'tooltip-activator-' + org.id;
    let tooltipElement = 'tooltip-info-' + org.id;

    html += '<div class="single-organisation">';
    html += '<img class="organization-logo-ds ' + tooltipButton + '" data-id="' + org.id + '" src="' + org.img + '" alt="' + org.name + '">';
    html += '<div class="organization-tooltip ' + tooltipElement + '" role="tooltip"><h4><a href="' + org.link + '" rel="noopener noreferrer">' + org.name + '</a></h4><br>' + org.desc + '<div class="tooltip-arrow" data-popper-arrow></div></div>';
    html += '</div>';

  }

  html += "</div>";
  $('.organization-loader').fadeOut(400).after(html);
  $('.donation-organization-container').fadeIn(600);

  const container = $('#donation-organization-container');

  container.on('mouseenter focus', '.organization-logo-ds', function () {
    const button = document.querySelector('.tooltip-activator-' + $(this).data('id'));
    const tooltip = document.querySelector('.tooltip-info-' + $(this).data('id'));
    showTooltip(button, tooltip);
  });

  container.on('mouseleave blur', '.organization-logo-ds', function () {
    const tooltip = document.querySelector('.tooltip-info-' + $(this).data('id'));
    hideTooltip(tooltip);
  });

  container.on('click', '.organization-logo-ds', function () {
    let radioInputID = '#donation_organization_' + $(this).data('id');
    $('.selected-organization').removeClass('selected-organization');
    $(this).parent().addClass('selected-organization');
    $(radioInputID).click();
  });

  let popperInstance = null;

  function showTooltip(button, tooltip) {
    tooltip.setAttribute('data-show', '');
    createTooltip(button, tooltip);
  }

  function hideTooltip( tooltip) {
    tooltip.removeAttribute('data-show');
    destroyTooltip();
  }


  function createTooltip(button, tooltip) {
    popperInstance = Popper.createPopper(button, tooltip, {
      modifiers: [
            {
              name: 'offset',
              options: {
                offset: [0, 8],
              },
            },
          ],
    });
  }

  function destroyTooltip() {
    if (popperInstance) {
      popperInstance.destroy();
      popperInstance = null;
    }
  }


});

