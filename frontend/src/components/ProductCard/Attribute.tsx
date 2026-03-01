import React from "react";
import type { Product, ProductData } from "../../types";
import type { Attribute as AttributeType } from "../../types";

type Props = {
  attribute: AttributeType;
  selectedAttributes: Record<string, string>;
  onSelect: (attrID: string, value: string) => void;
};
function Attribute({ attribute, selectedAttributes, onSelect }: Props) {
  return (
    <div>
      {" "}
      <div className="my-2">
        <div className="font-bold mb-2">{attribute.name}</div>
        <div className="flex flex-1">
          <div
            className={`grid  ${attribute.name === "Color" ? "grid-cols-5 gap-3" : "grid-cols-4 gap-4"}`}
          >
            {attribute.items.map((item) => (
              <button
                key={item.id}
                onClick={() => onSelect(attribute.id, item.value)}
                className={`cursor-pointer border w-12 h-10 text-sm font-medium transition-colors
                        border-black text-center ${selectedAttributes[attribute.name] === item.value ? "border-3 border-green-600" : ""}`}
                style={
                  attribute.name === "Color"
                    ? {
                        backgroundColor: item.value,
                        width: "30px",
                        height: "30px",
                      }
                    : {}
                }
              >
                {attribute.name === "Color" ? "" : item.value}
              </button>
            ))}
          </div>
        </div>
      </div>
    </div>
  );
}

export default Attribute;
