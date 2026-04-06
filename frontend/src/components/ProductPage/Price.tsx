import type { Price } from "../../types";

type Props = {
  prices: Price[];
};

const ProductPrice = ({ prices }: Props) => {
  return (
    <div className="my-4">
      <p className="font-bold uppercase text-sm tracking-wide">Price:</p>

      <p className="text-2xl font-bold mt-1">
        {prices[0].currency.symbol}
        {prices[0].amount.toFixed(2)}
      </p>
    </div>
  );
};

export default ProductPrice;
