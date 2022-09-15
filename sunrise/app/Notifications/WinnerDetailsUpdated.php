<?php

namespace App\Notifications;

use App\Entities\ApplicationFile;
use App\Entities\ApplicationWinner;
use App\Traits\HasEntityManager;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class WinnerDetailsUpdated extends Notification implements ShouldQueue
{
    use Queueable;
    use HasEntityManager;

    /**
     * @var int
     */
    public $id;

    /**
     * @var int
     */
    protected $winnerId;

    /**
     * Create a new notification instance.
     *
     * @param ApplicationWinner|int $winner
     */
    public function __construct($winner)
    {
        $this->winnerId = ($winner instanceof ApplicationWinner) ? $winner->getId() : $winner;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        /** @var ApplicationWinner $winner */
        $winner = $this->em()->find(ApplicationWinner::class, $this->winnerId);

        $message = (new MailMessage)
            ->greeting('Winner details has been changed!')
            ->line(sprintf('Name: %s', $winner->getName()))
            ->line(sprintf('Email: %s', $winner->getEmail()))
            ->line(sprintf('Phone: %s', phone_format_us($winner->getPhone())))
            ->line(sprintf('Date of birth: %s', $winner->getDateOfBirth()->format('Y-m-d')))
            ->line('')
            ->line(sprintf('City: %s', $winner->getCity()))
            ->line(sprintf('State: %s', $winner->getState()->getName()))
            ->line(sprintf('Address: %s %s', $winner->getAddress(), $winner->getAddress2()))
            ->line(sprintf('ZIP Code: %s', $winner->getZip()))
            ->line('')
            ->line(sprintf('Testimonial: %s', $winner->getTestimonial()))
            ->attach($winner->getPhoto()->getFile(), [
                'as' => $winner->getPhoto()->getName(),
                'mime' => $winner->getPhoto()->getMimeType(),
            ]);

        /** @var ApplicationFile $affidavit */
        foreach ($winner->getAffidavit() as $affidavit) {
            $message->attach($affidavit->getFile(), [
                'as' => $affidavit->getName(),
                'mime' => $affidavit->getMimeType(),
            ]);
        }

        if (
            $winner->getBankName() ||
            $winner->getNameOfAccount() ||
            $winner->getAccountNumber() ||
            $winner->getRoutingNumber() ||
            $winner->getSwiftCode()
        ) {
            $message
                ->line('')
                ->line('Bank details:')
                ->line(sprintf('Bank Name: %s', $winner->getBankName()))
                ->line(sprintf('Name of account: %s', $winner->getNameOfAccount()))
                ->line(sprintf('Account number: %s', $winner->getAccountNumber()))
                ->line(sprintf('Routing number: %s', $winner->getRoutingNumber()))
                ->line(sprintf('Swift code: %s', $winner->getSwiftCode()));
        }

        if ($winner->getPaypal()) {
            $message
                ->line('')
                ->line('Paypal account:')
                ->line($winner->getPaypal());
        }

        $message->action('Show in admin', route('index', ['any' => sprintf('/winners/%s', $winner->getId())]));
        return $message;
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
