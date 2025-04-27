
import React from "react";
import { Link } from "react-router-dom";
import { Button } from "@/components/ui/button";
import { ShoppingCart, Heart, Search, User } from "lucide-react";
import { useToast } from "@/hooks/use-toast";

const Navbar = () => {
  const { toast } = useToast();
  const cartCount = 0; // Will be replaced with actual cart count

  return (
    <header className="sticky top-0 z-50 w-full bg-background border-b">
      <div className="container mx-auto px-4">
        <div className="flex h-16 items-center justify-between">
          <div className="flex items-center">
            <Link to="/" className="text-2xl font-bold text-pharma-dark">
              PharmaConnect
            </Link>
          </div>
          
          <nav className="hidden md:flex items-center space-x-8">
            <Link to="/" className="text-foreground hover:text-primary transition">
              Home
            </Link>
            <Link to="/shop" className="text-foreground hover:text-primary transition">
              Shop
            </Link>
            <Link to="/track-order" className="text-foreground hover:text-primary transition">
              Track Order
            </Link>
            <Link to="/wishlist" className="text-foreground hover:text-primary transition">
              Wishlist
            </Link>
            <Link to="/blog" className="text-foreground hover:text-primary transition">
              Blog
            </Link>
            <Link to="/contact" className="text-foreground hover:text-primary transition">
              Contact
            </Link>
          </nav>
          
          <div className="flex items-center space-x-4">
            <Button variant="ghost" size="icon" onClick={() => toast({ title: "Search", description: "Search functionality coming soon" })}>
              <Search className="h-5 w-5" />
            </Button>
            
            <Link to="/account">
              <Button variant="ghost" size="icon">
                <User className="h-5 w-5" />
              </Button>
            </Link>
            
            <Link to="/wishlist">
              <Button variant="ghost" size="icon">
                <Heart className="h-5 w-5" />
              </Button>
            </Link>
            
            <Link to="/cart" className="relative">
              <Button variant="ghost" size="icon">
                <ShoppingCart className="h-5 w-5" />
              </Button>
              {cartCount > 0 && (
                <span className="absolute -top-1 -right-1 bg-pharma-secondary text-white w-5 h-5 rounded-full flex items-center justify-center text-xs">
                  {cartCount}
                </span>
              )}
            </Link>
          </div>
        </div>
      </div>
    </header>
  );
};

export default Navbar;
