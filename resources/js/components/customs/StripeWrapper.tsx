import useConfig from "@/stores/config";
import { Elements } from "@stripe/react-stripe-js";
import { loadStripe } from "@stripe/stripe-js";

const stripePublicKey = (key: unknown): key is string =>
  typeof key === "string" && key.length > 0;

export const StripeWrapper = ({ children }: { children: React.ReactNode }) => {
  const { setting } = useConfig();

  const stripePromise =
    stripePublicKey(setting?.stripe_public) ? loadStripe(setting.stripe_public) : null;

  if (stripePromise) {
    return <Elements stripe={stripePromise}>{children}</Elements>;
  }

  return <>{children}</>;
};