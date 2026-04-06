type Props = {
  canAddToCart: boolean;
  onAddToCart: () => void;
};

const AddToCartButton = ({ canAddToCart, onAddToCart }: Props) => {
  return (
    <button
      data-testid="add-to-cart"
      onClick={onAddToCart}
      disabled={!canAddToCart}
      className={`
        w-full sm:w-80 py-3 sm:py-4
        text-white font-medium tracking-widest uppercase text-sm
        transition-colors
        ${
          canAddToCart
            ? "bg-green-500 hover:bg-green-600 cursor-pointer"
            : "bg-gray-300 cursor-not-allowed"
        }
      `}
    >
      ADD TO CART
    </button>
  );
};

export default AddToCartButton;
