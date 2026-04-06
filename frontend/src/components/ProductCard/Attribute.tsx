import React from "react";
import type { Attribute as AttributeType } from "../../types";

type Props = {
  attribute: AttributeType;
  selectedAttributes: Record<string, string>;
  onSelect: (attrID: string, value: string) => void;
};

const toKebabCase = (str: string) => str.toLowerCase().replace(/\s+/g, "-");

function Attribute({ attribute, selectedAttributes, onSelect }: Props) {
  return (
    <div
      className="my-4"
      data-testid={`product-attribute-${toKebabCase(attribute.name)}`}
    >
      {/* Attribute name */}
      <p className="font-bold mb-2 uppercase text-sm">{attribute.name}:</p>

      {/* Attribute items */}
      <div className="flex gap-2 flex-wrap">
        {attribute.items.map((item) => {
          const isSelected = selectedAttributes[attribute.id] === item.value;

          const kebabAttr = toKebabCase(attribute.name);
          const attrValue = item.value;

          const testId = isSelected
            ? `product-attribute-${kebabAttr}-${attrValue}-selected`
            : `product-attribute-${kebabAttr}-${attrValue}`;

          if (attribute.type === "swatch") {
            return (
              <button
                key={item.id}
                onClick={() => onSelect(attribute.id, item.value)}
                title={item.displayValue}
                style={{ backgroundColor: item.value }}
                data-testid={testId}
                className={`w-8 h-8 border-2 transition-all ${
                  isSelected
                    ? "border-green-500 scale-110"
                    : "border-gray-300 hover:border-black"
                }`}
              />
            );
          }

          // Text attribute
          return (
            <button
              key={item.id}
              onClick={() => onSelect(attribute.id, item.value)}
              data-testid={testId}
              className={`border px-4 py-2 text-sm font-medium transition-colors ${
                isSelected
                  ? "bg-black text-white border-black"
                  : "bg-white text-black border-gray-300 hover:border-black"
              }`}
            >
              {item.value}
            </button>
          );
        })}
      </div>
    </div>
  );
}

export default Attribute;
