 <div>

     {{ $this->table }}

     <script>
         document.addEventListener('livewire:load', function() {
             Livewire.on('recordUpdated', function() {
                 // Show your modal here
                 // Example: $('#yourModalId').modal('show');
                 // Replace 'yourModalId' with the ID of your modal

                 console.log('testing');
             });
         });
     </script>
 </div>