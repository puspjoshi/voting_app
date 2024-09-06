<x-modal-confirm 
  livewire-event-to-open-modal='mark-as-spam-comment-modal'
  event-to-close-modal='comment-was-marked-as-spam'
  modal-title='Mark Comment As Spam'
  modal-description='Are you sure want to mark this Comment as spam?'
  modal-confirm-button-text='Mark as Spam'
  wire-click='markAsSpamComment'
/>