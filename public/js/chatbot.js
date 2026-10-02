(() => {
  const root = document.querySelector('[data-chatbot-root]');
  if (!root) return;

  try {
    if (!sessionStorage.getItem('timlyne-chatbot-session')) {
      sessionStorage.setItem('timlyne-chatbot-session', 'active');
    }
  } catch (error) {
    // Conversation continuity is maintained by the PHP session.
  }

  const csrfToken = root.dataset.csrfToken || '';
  const welcomeMessage = root.dataset.welcomeMessage || '';
  const quickActions = [
    ['Products', 'What products do you offer?'],
    ['Prices', 'What are your prices?'],
    ['Location', 'where are you located'],
    ['Hours', 'what are your opening hours'],
    ['FAQs', 'do you deliver products'],
    ['Contact Us', 'How can I contact you?'],
    ['Chat on WhatsApp', 'I would like to speak to a human'],
  ];

  const launcher = document.createElement('button');
  launcher.type = 'button';
  launcher.className = 'chatbot-launcher';
  launcher.setAttribute('aria-label', 'Open chat');
  launcher.setAttribute('aria-expanded', 'false');
  launcher.setAttribute('aria-controls', 'chatbot-panel');
  launcher.textContent = 'Chat';

  const panel = document.createElement('section');
  panel.id = 'chatbot-panel';
  panel.className = 'chatbot-panel';
  panel.setAttribute('role', 'dialog');
  panel.setAttribute('aria-modal', 'false');
  panel.setAttribute('aria-labelledby', 'chatbot-title');
  panel.hidden = true;

  const header = document.createElement('header');
  header.className = 'chatbot-header';
  const title = document.createElement('h2');
  title.id = 'chatbot-title';
  title.textContent = 'Chat with us';
  const closeButton = document.createElement('button');
  closeButton.type = 'button';
  closeButton.className = 'chatbot-close';
  closeButton.setAttribute('aria-label', 'Close chat');
  closeButton.textContent = 'Close';
  header.append(title, closeButton);

  const history = document.createElement('div');
  history.className = 'chatbot-history';
  history.setAttribute('role', 'log');
  history.setAttribute('aria-live', 'polite');
  history.setAttribute('aria-relevant', 'additions text');

  const actions = document.createElement('nav');
  actions.className = 'chatbot-quick-actions';
  actions.setAttribute('aria-label', 'Quick questions');

  const form = document.createElement('form');
  form.className = 'chatbot-form';
  const input = document.createElement('input');
  input.id = 'chatbot-message';
  input.name = 'message';
  input.type = 'text';
  input.autocomplete = 'off';
  input.placeholder = 'Type your message';
  input.setAttribute('aria-label', 'Your message');
  input.required = true;
  const sendButton = document.createElement('button');
  sendButton.type = 'submit';
  sendButton.textContent = 'Send';
  form.append(input, sendButton);

  let welcomeAdded = false;
  let sending = false;

  function scrollToLatest() {
    history.scrollTop = history.scrollHeight;
  }

  function appendMessage(text, kind, whatsappUrl = '') {
    const message = document.createElement('div');
    message.className = `chatbot-message chatbot-message--${kind}`;
    const paragraph = document.createElement('p');
    paragraph.textContent = text;
    message.appendChild(paragraph);

    if (whatsappUrl) {
      const handoff = document.createElement('a');
      handoff.className = 'chatbot-handoff';
      handoff.href = whatsappUrl;
      handoff.target = '_blank';
      handoff.rel = 'noopener noreferrer';
      handoff.textContent = 'Chat on WhatsApp';
      message.appendChild(handoff);
    }

    history.appendChild(message);
    scrollToLatest();
  }

  function setOpen(isOpen) {
    panel.hidden = !isOpen;
    launcher.setAttribute('aria-expanded', String(isOpen));
    launcher.setAttribute('aria-label', isOpen ? 'Close chat' : 'Open chat');
    if (isOpen) {
      if (!welcomeAdded) {
        appendMessage(welcomeMessage, 'bot');
        welcomeAdded = true;
      }
      input.focus();
    } else {
      launcher.focus();
    }
  }

  async function sendMessage(rawMessage) {
    const message = rawMessage.trim();
    if (!message || sending) return;

    appendMessage(message, 'user');
    input.value = '';
    sending = true;
    input.disabled = true;
    sendButton.disabled = true;
    actions.querySelectorAll('button').forEach((button) => { button.disabled = true; });
    form.setAttribute('aria-busy', 'true');

    try {
      const body = new URLSearchParams();
      body.set('csrf_token', csrfToken);
      body.set('message', message);
      const response = await fetch('/chatbot/ask', {
        method: 'POST',
        credentials: 'same-origin',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded;charset=UTF-8' },
        body,
      });
      const result = await response.json();
      if (!response.ok || typeof result.reply !== 'string') {
        throw new Error('Chat request failed');
      }
      appendMessage(result.reply, 'bot', result.handoff ? result.whatsappUrl : '');
    } catch (error) {
      appendMessage('Something went wrong, please try again or contact us on WhatsApp', 'bot');
    } finally {
      sending = false;
      input.disabled = false;
      sendButton.disabled = false;
      actions.querySelectorAll('button').forEach((button) => { button.disabled = false; });
      form.removeAttribute('aria-busy');
      input.focus();
    }
  }

  quickActions.forEach(([label, message]) => {
    const button = document.createElement('button');
    button.type = 'button';
    button.textContent = label;
    if (label === 'Chat on WhatsApp') {
      button.addEventListener('click', () => {
        const whatsappUrl = root.dataset.whatsappUrl;
        if (whatsappUrl) window.open(whatsappUrl, '_blank', 'noopener,noreferrer');
      });
    } else {
      button.addEventListener('click', () => sendMessage(message));
    }
    actions.appendChild(button);
  });

  form.addEventListener('submit', (event) => {
    event.preventDefault();
    sendMessage(input.value);
  });
  launcher.addEventListener('click', () => setOpen(panel.hidden));
  closeButton.addEventListener('click', () => setOpen(false));
  document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape' && !panel.hidden) setOpen(false);
  });

  panel.append(header, history, actions, form);
  root.append(panel, launcher);
})();
