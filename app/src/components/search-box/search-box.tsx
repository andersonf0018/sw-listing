"use client";

import { ChangeEvent, ChangeEventHandler } from "react";
import { Button, Input, RadioGroup, RadioGroupItem, Label } from "../ui";
import { useSearchBoxStore } from "@/stores";

interface SearchBoxProps {
  title?: string;
  placeholder?: string;
  options?: string[];
  onChange?: ChangeEventHandler<HTMLInputElement>;
  onSearch?: () => void;
  isSearching?: boolean;
}

export const SearchBox = ({
  title = "What are you searching for?",
  placeholder = "e.g. Chewbaccam, Yoda, Boba Fett",
  options = [],
  onChange,
  onSearch,
  isSearching = false,
}: SearchBoxProps) => {
  const { search, setSearch, selectedOption, setSelectedOption } = useSearchBoxStore();

  const searchEnabled = search.length > 0 && selectedOption;

  const handleChange = (e: ChangeEvent<HTMLInputElement>) => {
    setSearch(e.target.value);
    onChange?.(e);
  };

  const handleSearch = () => {
    if (!isSearching && searchEnabled) {
      onSearch?.();
    }
  };

  return (
    <div className="flex flex-col gap-5">
      <h2 className="text-sm font-medium">{title}</h2>
      {options.length > 0 && (
        <RadioGroup
          className="flex flex-wrap gap-6"
          value={selectedOption ?? ""}
          onValueChange={setSelectedOption}
        >
          {options.map((option) => (
            <div key={option} className="flex items-center gap-2">
              <RadioGroupItem id={option} value={option} />
              <Label className="text-sm font-bold" htmlFor={option}>
                {option}
              </Label>
            </div>
          ))}
        </RadioGroup>
      )}
      <Input
        className="font-bold py-5"
        value={search}
        placeholder={placeholder}
        onChange={handleChange}
      />
      <Button
        className="font-bold disabled:bg-gray-400 cursor-pointer disabled:cursor-not-allowed"
        onClick={handleSearch}
        disabled={!searchEnabled}
      >
        {isSearching ? "SEARCHING..." : "SEARCH"}
      </Button>
    </div>
  );
};
