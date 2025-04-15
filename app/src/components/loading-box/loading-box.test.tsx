import { render, screen } from "@testing-library/react";
import { LoadingBox } from "./loading-box";

describe("LoadingBox", () => {
  it("renders a loading spinner with accessible text", () => {
    const { container } = render(<LoadingBox />);
    
    expect(screen.getByText("Loading...")).toBeInTheDocument();
    
    const svg = container.querySelector("svg");
    expect(svg).not.toBeNull();
  });

  it("has animation class applied to the spinner", () => {
    const { container } = render(<LoadingBox />);
    
    const svg = container.querySelector("svg");
    expect(svg).toHaveClass("animate-spin");
  });

  it("has proper accessibility attributes", () => {
    const { container } = render(<LoadingBox />);
    
    const svg = container.querySelector("svg");
    expect(svg).toHaveAttribute("aria-hidden", "true");
    
    const srOnly = screen.getByText("Loading...");
    expect(srOnly).toHaveClass("sr-only");
  });

  it("renders in a centered container", () => {
    const { container } = render(<LoadingBox />);
    
    const div = container.firstChild as HTMLElement;
    expect(div).toHaveClass("flex");
    expect(div).toHaveClass("justify-center");
    expect(div).toHaveClass("items-center");
  });
});
