<x-modal-confirm 
  event-to-open-modal='custom-mark-as-spam-modal'
  event-to-close-modal='idea-was-marked-as-spam'
  modal-title='Report as a Spam'
  modal-description='Are you sure want to report this idea as Spam? This action cannot be undone.'
  modal-confirm-button-text='Mark as a spam'
  wire-click='markAsSpam'
/>
  