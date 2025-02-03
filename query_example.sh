curl -X POST "http://localhost:8000/api/login" \
     -H "Content-Type: application/json" \
     -d '{"email":"test@example.com","password":"password123"}' \
     -v -c cookies.txt

curl -X POST "http://localhost:8000/api/login" \
     -H "Content-Type: application/json" \
     -d '{"email":"test@example.com","password":"password123"}' \
     -c cookies.txt

curl -X POST "http://localhost:8000/api/conversations" \
     -H "Content-Type: application/json" \
     -b cookies.txt \
     -d '{"title": "My First Chat"}'

curl -X GET "http://localhost:8000/api/conversations" \
     -H "Content-Type: application/json" \
     -b cookies.txt

curl -X POST "http://localhost:8000/api/conversations/4/messages" \
     -H "Content-Type: application/json" \
     -b cookies.txt \
     -d '{"content": "Hello, this is my first message!"}'