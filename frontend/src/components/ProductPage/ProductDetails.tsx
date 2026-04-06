import type { AttributeSet, Price } from "../../types";

import Attribute from "../ProductCard/Attribute";
import ProductPrice from "./Price";
import AddToCartButton from "./AddToCartButton";

type Props = {
  name: string;
  description: string;
  prices: Price[];
  attributes: AttributeSet[];
  selectedAttributes: Record<string, string>;
  canAddToCart: boolean;
  onSelect: (attID: string, value: string) => void;
  onAddToCart: () => void;
};

const ProductDetails = ({
  name,
  description,
  prices,
  attributes,
  selectedAttributes,
  canAddToCart,
  onSelect,
  onAddToCart,
}: Props) => {
  return (
    <div className="flex flex-col lg:w-80 xl:w-96">
      {/* Name */}
      <h1 className="text-2xl sm:text-3xl font-semibold mb-4">{name}</h1>

      {/* Attributes */}
      {attributes.map((attribute, index) => (
        <Attribute
          key={index}
          attribute={attribute}
          selectedAttributes={selectedAttributes}
          onSelect={onSelect}
        />
      ))}

      {/* Price */}
      <ProductPrice prices={prices} />

      {/* Add to cart */}
      <AddToCartButton canAddToCart={canAddToCart} onAddToCart={onAddToCart} />

      {/* Description */}
      <div
        data-testid="product-description"
        className="mt-6 text-gray-600 text-sm leading-relaxed"
      >
        {description.replace(/<[^>]*>/g, "")}
      </div>
    </div>
  );
};

export default ProductDetails;
