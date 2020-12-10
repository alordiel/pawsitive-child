/* global jQuery */
jQuery(function ($) {

  // On click of selected organization
  $('.single-organisation').on('click', function () {
    const organization = $('.organization-logo-ds', this);

    const isActive = $(this).hasClass('active');
    const id = $(organization).data('id');
    const title = $(organization).data('title');
    const link = $(organization).data('link');
    const collected = $(organization).data('collected');
    const excerpt = $(organization).data('excerpt');

    let primaryButton =$('.modal .btn-primary');
    primaryButton.attr('data-id', id);
    if (isActive) {
      primaryButton.prop('disabled', true);
    } else {
      primaryButton.prop('disabled', false);
    }
    $('.modal-title').text(title);
    $('.modal-excerpt').text(excerpt);
    $('.total-collected').text(collected);
    $('.modal-link-view-more').attr('href', link);

    openModal();
  });

  // On close of Modal
  $('.modal .btn-secondary, .modal-header .close').on('click', function () {
    closeModal();
  });

  // On selecting an organization form the modal
  $('.modal .btn-primary').on('click', function () {
    const id = $(this).attr('data-id');
    console.log(id);
    $('.single-organisation').removeClass('active');
    $('#organization-' + id).addClass('active');
    closeModal();
  });

  function closeModal() {
    // remove classes
    $('body').removeClass('modal-open');
    $('#organization-short-info').removeClass('show');
    // clean up the title, content and etc from the modal
    $('.modal-title').text('');
    $('.modal-excerpt').text('');
    $('.total-collected').text('');
     $('.modal-link-view-more').attr('href', '');
  }

  function openModal() {
    $('#organization-short-info').addClass('show');
    $('body').addClass('modal-open')
  }
});

