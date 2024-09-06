<x-modal-confirm 
  {{-- event-to-open-modal='custom-show-delete-comment-modal' --}}
  livewire-event-to-open-modal='delete-comment-modal'
  event-to-close-modal='commentWasDeleted'
  modal-title='Delete Comment'
  modal-description='Are you sure want to delete this Comment? This action cannot be undone.'
  modal-confirm-button-text='Delete'
  wire-click='deleteComment'
/>