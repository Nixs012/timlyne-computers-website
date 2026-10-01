#!/bin/bash
curl -s http://localhost:8000/faq | grep -c "rel=\"canonical\""
curl -s http://localhost:8000/gallery | grep -c "rel=\"canonical\""
curl -s http://localhost:8000/privacy-policy | grep -c "rel=\"canonical\""
curl -s http://localhost:8000/terms-conditions | grep -c "rel=\"canonical\""
curl -s http://localhost:8000/faq | grep -c "<html"
curl -s http://localhost:8000/faq | grep -c "</html>"
curl -s http://localhost:8000/privacy-policy | grep -c "<html"
curl -s http://localhost:8000/privacy-policy | grep -c "</html>"
curl -s http://localhost:8000/terms-conditions | grep -c "<html"
curl -s http://localhost:8000/terms-conditions | grep -c "</html>"
curl -s http://localhost:8000/privacy-policy | grep "rel=\"canonical\""
curl -s http://localhost:8000/terms-conditions | grep "rel=\"canonical\""
curl -s http://localhost:8000/api/products | head -c 20
