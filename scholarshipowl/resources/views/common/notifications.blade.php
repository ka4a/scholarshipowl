@if(\Auth::user() instanceof \App\Entity\Account)
<script type="application/javascript">
(function(){
  window.SOWLNotifications = {};

  window.SOWLNotifications['dedicatedEmailNotification'] = '{!! map_tags_provider(
      'To keep your personal inbox clean, your scholarship applications will be submitted from [[email]]',
      [ \Auth::user() ]
  )!!}';

 window.SOWLNotifications['_sonote2'] = "Don't miss out on the Double Your Scholarship grant. We match the value " +
   "of scholarships you are awarded through ScholarshipOwl's application engine. " +
   "<a style='color: #fff' target='_blank' href='/lp/double-your-scholarship'>Click here for more information</a>.";
})();
</script>
@endif
