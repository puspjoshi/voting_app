<x-modal-confirm 
  livewire-event-to-open-modal='mark-as-not-spam-comment-modal'
  event-to-close-modal='comment-was-marked-as-not-spam'
  modal-title='Reset Spam Counter'
  modal-description='Are you sure want to mark this Comment as not spam?'
  modal-confirm-button-text='Reset Spam Counter'
  wire-click='markAsNotSpamComment'
/>