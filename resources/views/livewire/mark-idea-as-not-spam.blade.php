<x-modal-confirm 
  event-to-open-modal='custom-mark-as-not-spam-modal'
  event-to-close-modal='idea-was-marked-as-not-spam'
  modal-title='Reset a Spam counter'
  modal-description='Are you sure want to remove this idea as Spam? This action cannot be undone.'
  modal-confirm-button-text='Reset form spam'
  wire-click='markAsNotSpam'
/>