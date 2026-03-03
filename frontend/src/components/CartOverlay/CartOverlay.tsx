import { useCart } from "../../context/CartContext";
import { useMutation } from "@apollo/client/react";
import { PLACE_ORDER } from "../../graphql/queries";
import { CartOfItem } from "./CartItem";
type Props = {
  //visibility: boolean;
};

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
    console.log("mutation variables:", items);
    try {
      const result = await placeOrder({ variables: { items } });
      console.log("mutation result:", result);
      clearCart();
      setIsCartOpen(false);
    } catch (error) {
      console.error("Order Failed :", error);
    }
  };
  return (
    <>
      {/* {/* FLOATING CART PANEL */}
      {isCartOpen && (
        <div
          data-testid="cart-overlay"
          className="fixed left-0 right-0 bottom-0 opacity-40 bg-gray-500  z-40"
          style={{ top: "80px" }}
          onClick={() => setIsCartOpen(false)}
        />
      )}
      <div
        id="cart-panel"
        className={`${isCartOpen ? "visible" : "invisible"}  absolute right-0 top-full w-96 bg-white shadow-xl z-50 p-6`}
      >
        {/* Title */}
        <p className="text-base mb-6">
          <span className="font-bold">My Bag,</span>{" "}
          {totalCount === 1 ? "1 Item" : `${totalCount} items`}
        </p>

        {cartItems.length === 0 ? (
          <p className="text-gray-400 text-sm py-4">Your cart is empty</p>
        ) : (
          cartItems.map((item, index) => (
            <CartOfItem key={index} item={item} index={index} />
          ))
        )}

        {/* Total */}
        <div
          className="flex justify-between items-center mt-4 pt-2"
          data-testid="cart-total"
        >
          <span className="font-medium text-sm">Total</span>
          <span className="font-bold text-lg">$ {totalPrice.toFixed(2)}</span>
        </div>

        {/* Place Order button */}
        <button
          onClick={handlePlaceOrder}
          disabled={cartItems.length === 0}
          className={`w-full py-3 mt-4   ${cartItems.length === 0 ? "bg-gray-500" : "bg-green-500 hover:bg-green-600"}  text-white text-sm font-medium tracking-widest uppercase transition-colors`}
        >
          Place Order
        </button>
      </div>
      {/* END CART PANEL */}
    </>
  );
};
