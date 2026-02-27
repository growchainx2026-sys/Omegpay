import useConfig from "@/stores/config";
import { Elements } from "@stripe/react-stripe-js";
import { loadStripe } from "@stripe/stripe-js";

export const StripeWrapper = ({ children }: { children: React.ReactNode }) => {

    const {setting} = useConfig();

    const stripePromise = loadStripe(setting?.stripe_public as string);

  return (
    <Elements stripe={stripePromise}>
      {children}
    </Elements>
  );
}