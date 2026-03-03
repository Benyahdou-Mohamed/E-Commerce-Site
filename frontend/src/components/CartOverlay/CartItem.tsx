import { useCart } from "../../context/CartContext";
import type { CartItem } from "../../types";

type Props = {
  item: CartItem;
  index: number;
};

const toKebabCase = (str: string) => str.toLowerCase().replace(/\s+/g, "-");

export const CartOfItem = ({ item, index }: Props) => {
  const { increaseQuantity, decreaseQuantity } = useCart();

  return (
    <div className="flex gap-3 py-4 border-b border-gray-200">
      {/* Info */}
      <div className="flex-1">
        <p className="font-light text-base">{item.name}</p>
        <p className="font-medium mt-1">
          {item.currencySymbol}
          {item.price.toFixed(2)}
        </p>

        {/* Attributes */}
        {item.attributes.map((attribute) => (
          <div
            className="mt-3"
            key={attribute.id}
            data-testid={`cart-item-attribute-${toKebabCase(attribute.name)}`}
          >
            <p className="text-xs text-gray-500 mb-1">{attribute.name}:</p>
            <div className="flex gap-1 flex-wrap">
              {attribute.items.map((attr) => {
                const isSelected =
                  item.selectedAttributes[attribute.id] === attr.value;

                const kebabAttr = toKebabCase(attribute.name);
                const kebabVal = toKebabCase(attr.value);
                const testId = isSelected
                  ? `cart-item-attribute-${kebabAttr}-${kebabVal}-selected`
                  : `cart-item-attribute-${kebabAttr}-${kebabVal}`;

                if (attribute.type === "swatch") {
                  return (
                    <div
                      key={attr.id}
                      data-testid={testId}
                      style={{ backgroundColor: attr.value }}
                      className={`w-6 h-6 border-2 ${
                        isSelected ? "border-green-500" : "border-transparent"
                      }`}
                    />
                  );
                }

                return (
                  <div
                    key={attr.id}
                    data-testid={testId}
                    className={`border px-2 py-0.5 text-xs min-w-[28px] text-center ${
                      isSelected
                        ? "bg-black text-white border-black"
                        : "bg-white text-black border-gray-300"
                    }`}
                  >
                    {attr.value}
                  </div>
                );
              })}
            </div>
          </div>
        ))}
      </div>

      {/* Quantity */}
      <div className="flex flex-col items-center justify-between py-1">
        <button
          data-testid="cart-item-amount-increase"
          onClick={() => increaseQuantity(index)}
          className="w-7 h-7 border border-black flex items-center justify-center text-lg hover:bg-gray-100"
        >
          +
        </button>
        <span data-testid="cart-item-amount" className="font-medium text-sm">
          {item.quantity}
        </span>
        <button
          data-testid="cart-item-amount-decrease"
          onClick={() => decreaseQuantity(index)}
          className="w-7 h-7 border border-black flex items-center justify-center text-lg hover:bg-gray-100"
        >
          −
        </button>
      </div>

      {/* Image */}
      <div className="w-24 h-24 flex-shrink-0">
        <img
          src={item.image}
          alt={item.name}
          className="w-full h-full object-cover"
        />
      </div>
    </div>
  );
};
