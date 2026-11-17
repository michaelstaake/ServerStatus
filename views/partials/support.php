<?php global $support_section, $support_link; ?>
<?php if (!empty($support_section)): ?>
<section class="support" aria-labelledby="support-title">
  <div class="wrap support__inner">
    <h2 id="support-title" class="support__title">Need support?</h2>
    <p class="support__lead">If something looks off, or you need help with an account, we're here.</p>
    <div class="support__actions">
      <a class="btn btn--primary" href="https://napa-ai.com/contact">Contact us</a>
      <a class="btn" href="<?php echo ss_e(!empty($support_link) ? $support_link : 'https://portal.napa-ai.com/submitticket.php'); ?>">Submit a ticket</a>
    </div>
    <p class="support__alt">
      Can't sign in to submit a ticket? Message us on <a href="https://x.com/realNapaAI" target="_blank" rel="noopener noreferrer">X</a> (@realNapaAI) or <a href="tel:+17073060004">call (707) 306-0004</a>.
    </p>
  </div>
</section>
<?php endif; ?>
