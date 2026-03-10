<div class="wpsmp-plans-container">
    <?php if ( ! empty( $plans ) ) : ?>
        <ul class="wpsmp-plans-list" style="list-style: none; padding: 0; display: flex; gap: 20px;">
            <?php foreach ( $plans as $plan ) : ?>
                <li class="wpsmp-plan" style="border: 1px solid #ccc; padding: 20px; text-align: center;">
                    <h3><?php echo esc_html( $plan->name ); ?></h3>
                    <p><?php echo esc_html( $plan->description ); ?></p>
                    <p><strong>$<?php echo esc_html( $plan->price ); ?> / <?php echo esc_html( $plan->duration ); ?></strong></p>
                    <button class="wpsmp-subscribe-btn" data-plan-id="<?php echo esc_attr( $plan->id ); ?>">Subscribe</button>
                </li>
            <?php endforeach; ?>
        </ul>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var buttons = document.querySelectorAll('.wpsmp-subscribe-btn');
                buttons.forEach(function(btn) {
                    btn.addEventListener('click', function() {
                        var planId = this.getAttribute('data-plan-id');
                        this.innerText = 'Processing...';
                        this.disabled = true;
                        
                        fetch(wpsmp_ajax.ajax_url, {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                            body: 'action=wpsmp_process_checkout&plan_id=' + planId
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                window.location.href = data.data.url;
                            } else {
                                this.innerText = 'Subscribe';
                                this.disabled = false;
                                alert(data.data);
                            }
                        })
                        .catch(err => {
                            this.innerText = 'Subscribe';
                            this.disabled = false;
                            alert('An error occurred.');
                        });
                    });
                });
            });
        </script>
    <?php else : ?>
        <p>No subscription plans available at the moment.</p>
    <?php endif; ?>
</div>
