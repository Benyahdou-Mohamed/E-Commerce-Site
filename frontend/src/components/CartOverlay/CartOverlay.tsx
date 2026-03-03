import { useCart } from "../../context/CartContext";
import { useMutation } from "@apollo/client/react";
import { PLACE_ORDER } from "../../graphql/queries";
import { CartOfItem } from "./CartItem";

export const CartOverlay = () => {
  const {
    cartItems,
    clearCart,
    isCartOpen,
    setIsCartOpen,
    totalCount,
    totalPrice,
  } = useCart();

  const [placeOrder] = useMutation(PLACE_ORDER);

  const handlePlaceOrder = async (): Promise<void> => {
    const items = cartItems.map((item) => ({
      productId: item.id,
      quantity: item.quantity,
      selectedAttributes: JSON.stringify(item.selectedAttributes),
    }));

    try {
      const result = await placeOrder({ variables: { items } });
      clearCart();
      setIsCartOpen(false);
    } catch (error) {
      console.error("Order Failed:", error);
    }
  };

  return (
    <>
      {/* Dark overlay */}
      {isCartOpen && (
        <div
          className="fixed left-0 right-0 bottom-0 opacity-40 bg-gray-500 z-40"
          style={{ top: "80px" }}
          onClick={() => setIsCartOpen(false)}
        />
      )}

      {/* Cart panel */}
      <div
        id="cart-panel"
        data-testid="cart-overlay"
        className={`${
          isCartOpen ? "visible" : "invisible"
        } absolute right-0 top-full w-96 bg-white shadow-xl z-50 flex flex-col`}
        style={{ maxHeight: "calc(100vh - 80px)" }}
      >
        <div className="px-6 pt-6 pb-2 flex-shrink-0">
          <p className="text-base">
            <span className="font-bold">My Bag,</span>{" "}
            {totalCount === 1 ? "1 Item" : `${totalCount} Items`}
          </p>
        </div>

        <div className="px-6 overflow-y-auto flex-1">
          {cartItems.length === 0 ? (
            <p className="text-gray-400 text-sm py-4">Your cart is empty</p>
          ) : (
            cartItems.map((item, index) => (
              <CartOfItem key={index} item={item} index={index} />
            ))
          )}
        </div>

        <div className="px-6 pb-6 pt-2 flex-shrink-0 border-t">
          {/* Total */}
          <div
            data-testid="cart-total"
            className="flex justify-between items-center mt-4 pt-2"
          >
            <span className="font-medium text-sm">Total</span>
            <span className="font-bold text-lg">${totalPrice.toFixed(2)}</span>
          </div>

          {/* Place Order button */}
          <button
            onClick={handlePlaceOrder}
            disabled={cartItems.length === 0}
            className={`w-full py-3 mt-4 text-white text-sm font-medium tracking-widest uppercase transition-colors ${
              cartItems.length === 0
                ? "bg-gray-400 cursor-not-allowed"
                : "bg-green-500 hover:bg-green-600 cursor-pointer"
            }`}
          >
            Place Order
          </button>
        </div>
      </div>
    </>
  );
};
